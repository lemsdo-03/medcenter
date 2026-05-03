<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Appointment;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $patient = Patient::where('user_id', auth()->id())->firstOrFail();

        $conversations = Conversation::where('patient_id', $patient->id)
            ->with(['doctor', 'latestMessage'])
            ->get()
            ->sortByDesc(fn($c) => $c->latestMessage?->created_at);

        return view('patient.chat.index', compact('conversations', 'patient'));
    }

    public function show(Conversation $conversation)
    {
        $patient = Patient::where('user_id', auth()->id())->firstOrFail();

        if ($conversation->patient_id !== $patient->id) {
            abort(403);
        }

        $messages = $conversation->messages()->orderBy('created_at')->get();

        return view('patient.chat.show', compact('conversation', 'messages', 'patient'));
    }

    public function startChat(User $doctor)
    {
        $patient = Patient::where('user_id', auth()->id())->firstOrFail();

        if ($doctor->role !== 'doctor') {
            abort(404);
        }

        $hasAppointment = Appointment::where('patient_id', $patient->id)
            ->where('doctor_id', $doctor->id)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if (!$hasAppointment) {
            return back()->with('error', 'You can only chat with doctors you have an appointment with.');
        }

        $conversation = Conversation::firstOrCreate([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ]);

        return redirect()->route('patient.chat.show', $conversation);
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $patient = Patient::where('user_id', auth()->id())->firstOrFail();

        if ($conversation->patient_id !== $patient->id) {
            abort(403);
        }

        $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'patient',
            'sender_id' => $patient->id,
            'body' => $request->body,
        ]);

        return back();
    }

    public function sendFile(Request $request, Conversation $conversation)
    {
        $patient = Patient::where('user_id', auth()->id())->firstOrFail();

        if ($conversation->patient_id !== $patient->id) {
            abort(403);
        }

        $request->validate([
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx',
        ]);

        $file = $request->file('file');
        $path = $file->store('chat-files', 'public');

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'patient',
            'sender_id' => $patient->id,
            'body' => $request->body,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientMimeType(),
        ]);

        return back();
    }

    public function pollMessages(Conversation $conversation, Request $request)
    {
        $patient = Patient::where('user_id', auth()->id())->firstOrFail();

        if ($conversation->patient_id !== $patient->id) {
            abort(403);
        }

        $afterId = $request->get('after_id', 0);

        $messages = $conversation->messages()
            ->where('id', '>', $afterId)
            ->orderBy('created_at')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'sender_type' => $msg->sender_type,
                    'body' => $msg->body,
                    'file_path' => $msg->file_path ? asset('storage/' . $msg->file_path) : null,
                    'file_name' => $msg->file_name,
                    'file_type' => $msg->file_type,
                    'created_at' => $msg->created_at->format('h:i A'),
                ];
            });

        return response()->json(['messages' => $messages]);
    }
}
