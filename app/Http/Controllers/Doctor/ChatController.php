<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $conversations = Conversation::where('doctor_id', auth()->id())
            ->with(['patient', 'latestMessage'])
            ->get()
            ->sortByDesc(fn($c) => $c->latestMessage?->created_at);

        return view('doctor.chat.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        if ($conversation->doctor_id !== auth()->id()) {
            abort(403);
        }

        $messages = $conversation->messages()->orderBy('created_at')->get();
        $conversation->load('patient');

        return view('doctor.chat.show', compact('conversation', 'messages'));
    }

    public function reply(Request $request, Conversation $conversation)
    {
        if ($conversation->doctor_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'doctor',
            'sender_id' => auth()->id(),
            'body' => $request->body,
        ]);

        return back();
    }

    public function sendFile(Request $request, Conversation $conversation)
    {
        if ($conversation->doctor_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx',
        ]);

        $file = $request->file('file');
        $path = $file->store('chat-files', 'public');

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'doctor',
            'sender_id' => auth()->id(),
            'body' => $request->body,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientMimeType(),
        ]);

        return back();
    }

    public function pollMessages(Conversation $conversation, Request $request)
    {
        if ($conversation->doctor_id !== auth()->id()) {
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
