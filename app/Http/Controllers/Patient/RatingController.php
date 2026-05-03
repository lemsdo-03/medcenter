<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Rating;
use App\Models\Appointment;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function create()
    {
        $patient = Patient::where('user_id', auth()->id())->firstOrFail();

        $appointments = Appointment::where('patient_id', $patient->id)
            ->with('doctor')
            ->where('status', 'completed')
            ->orderBy('appointment_date', 'desc')
            ->get();

        return view('patient.ratings.create', compact('appointments', 'patient'));
    }

    public function store(Request $request)
    {
        $patient = Patient::where('user_id', auth()->id())->firstOrFail();

        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'type' => 'required|in:rating,complaint',
            'rating' => 'required_if:type,rating|nullable|integer|min:1|max:5',
            'comment' => 'required|string|max:2000',
        ]);

        $appointment = Appointment::where('id', $request->appointment_id)
            ->where('patient_id', $patient->id)
            ->firstOrFail();

        Rating::create([
            'patient_id' => $patient->id,
            'doctor_id' => $appointment->doctor_id,
            'appointment_id' => $appointment->id,
            'rating' => $request->type === 'rating' ? $request->rating : null,
            'comment' => $request->comment,
            'type' => $request->type,
        ]);

        return redirect()->route('patient.ratings.create')->with('success', 'Your ' . $request->type . ' has been submitted.');
    }
}
