<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Models\DoctorAvailability;
use App\Models\PatientNotification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index()
    {
        $patient = Patient::where('user_id', auth()->id())->firstOrFail();

        $appointments = $patient->appointments()
            ->with('doctor')
            ->orderBy('appointment_date', 'desc')
            ->paginate(15);

        return view('patient.appointments.index', compact('appointments', 'patient'));
    }

    public function create(User $doctor)
    {
        if ($doctor->role !== 'doctor') {
            abort(404);
        }

        return view('patient.appointments.create', compact('doctor'));
    }

    public function store(Request $request)
    {
        $patient = Patient::where('user_id', auth()->id())->firstOrFail();

        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
        ]);

        $appointmentDateTime = Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);

        if ($appointmentDateTime->isPast()) {
            return back()->withInput()->with('error', 'You cannot book an appointment in the past.');
        }

        $existingAppointment = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_date', $appointmentDateTime)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($existingAppointment) {
            return back()->withInput()->with('error', 'This time slot is already booked. Please choose another time.');
        }

        $dayOfWeek = strtolower($appointmentDateTime->format('l'));
        $time = $appointmentDateTime->format('H:i:s');

        $isAvailable = DoctorAvailability::where('doctor_id', $request->doctor_id)
            ->where('day_of_week', $dayOfWeek)
            ->whereRaw('TIME(start_time) <= ?', [$time])
            ->whereRaw('TIME(end_time) >= ?', [$time])
            ->where('is_available', true)
            ->exists();

        if (!$isAvailable) {
            return back()->withInput()->with('error', 'Doctor is not available at this time.');
        }

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $appointmentDateTime,
            'status' => 'scheduled',
            'notes' => $request->notes,
        ]);

        PatientNotification::create([
            'patient_id' => $patient->id,
            'appointment_id' => $appointment->id,
            'title' => 'Appointment Confirmed',
            'message' => 'Your appointment with Dr. ' . $appointment->doctor->name . ' on ' . $appointmentDateTime->format('M d, Y \a\t h:i A') . ' has been confirmed.',
        ]);

        return redirect()->route('patient.appointments.index')->with('success', 'Appointment booked successfully.');
    }

    public function edit(Appointment $appointment)
    {
        $patient = Patient::where('user_id', auth()->id())->firstOrFail();

        if ($appointment->patient_id !== $patient->id) {
            abort(403);
        }

        if ($appointment->status !== 'scheduled') {
            return back()->with('error', 'Only scheduled appointments can be edited.');
        }

        $appointment->load('doctor');

        return view('patient.appointments.edit', compact('appointment'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $patient = Patient::where('user_id', auth()->id())->firstOrFail();

        if ($appointment->patient_id !== $patient->id) {
            abort(403);
        }

        $request->validate([
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
        ]);

        $appointmentDateTime = Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);

        if ($appointmentDateTime->isPast()) {
            return back()->withInput()->with('error', 'You cannot book an appointment in the past.');
        }

        if ($appointment->appointment_date != $appointmentDateTime) {
            $existingAppointment = Appointment::where('doctor_id', $appointment->doctor_id)
                ->where('appointment_date', $appointmentDateTime)
                ->where('id', '!=', $appointment->id)
                ->where('status', '!=', 'cancelled')
                ->exists();

            if ($existingAppointment) {
                return back()->withInput()->with('error', 'This time slot is already booked.');
            }

            $dayOfWeek = strtolower($appointmentDateTime->format('l'));
            $time = $appointmentDateTime->format('H:i:s');

            $isAvailable = DoctorAvailability::where('doctor_id', $appointment->doctor_id)
                ->where('day_of_week', $dayOfWeek)
                ->whereRaw('TIME(start_time) <= ?', [$time])
                ->whereRaw('TIME(end_time) >= ?', [$time])
                ->where('is_available', true)
                ->exists();

            if (!$isAvailable) {
                return back()->withInput()->with('error', 'Doctor is not available at this time.');
            }
        }

        $appointment->update([
            'appointment_date' => $appointmentDateTime,
            'notes' => $request->notes,
        ]);

        PatientNotification::create([
            'patient_id' => $patient->id,
            'appointment_id' => $appointment->id,
            'title' => 'Appointment Updated',
            'message' => 'Your appointment with Dr. ' . $appointment->doctor->name . ' has been updated to ' . $appointmentDateTime->format('M d, Y \a\t h:i A') . '.',
        ]);

        return redirect()->route('patient.appointments.index')->with('success', 'Appointment updated successfully.');
    }

    public function cancel(Appointment $appointment)
    {
        $patient = Patient::where('user_id', auth()->id())->firstOrFail();

        if ($appointment->patient_id !== $patient->id) {
            abort(403);
        }

        if ($appointment->status === 'cancelled') {
            return back()->with('error', 'Appointment is already cancelled.');
        }

        $appointment->update(['status' => 'cancelled']);

        PatientNotification::create([
            'patient_id' => $patient->id,
            'appointment_id' => $appointment->id,
            'title' => 'Appointment Cancelled',
            'message' => 'Your appointment with Dr. ' . $appointment->doctor->name . ' on ' . $appointment->appointment_date->format('M d, Y \a\t h:i A') . ' has been cancelled.',
        ]);

        return redirect()->route('patient.appointments.index')->with('success', 'Appointment cancelled.');
    }
}
