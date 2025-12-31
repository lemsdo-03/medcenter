<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Models\DoctorAvailability;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor']);

        if ($request->has('date')) {
            $date = Carbon::parse($request->date);
            $query->whereDate('appointment_date', $date);
        } else {
            $query->whereDate('appointment_date', '>=', now());
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->orderBy('appointment_date')->paginate(20);

        return view('receptionist.appointments.index', compact('appointments'));
    }

    public function create(Request $request)
    {
        $patients = Patient::orderBy('last_name')->orderBy('first_name')->get();
        $doctors = User::where('role', 'doctor')->orderBy('name')->get();
        
        $selectedPatient = null;
        if ($request->has('patient_id')) {
            $selectedPatient = Patient::find($request->patient_id);
        }

        return view('receptionist.appointments.create', compact('patients', 'doctors', 'selectedPatient'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
            'is_paid' => 'nullable|boolean',
        ]);

        $appointmentDateTime = Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);

        if ($appointmentDateTime->isPast()) {
            return back()->withInput()->with('error', 'You cannot book an appointment in the past.');
        }

        $existingAppointment = Appointment::where('doctor_id', $validated['doctor_id'])
            ->where('appointment_date', $appointmentDateTime)
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingAppointment) {
            return back()->withInput()->with('error', 'This time slot is already booked. Please choose another time.');
        }

        $dayOfWeek = strtolower($appointmentDateTime->format('l'));
        $time = $appointmentDateTime->format('H:i:s');
        
        $isAvailable = DoctorAvailability::where('doctor_id', $validated['doctor_id'])
            ->where('day_of_week', $dayOfWeek)
            ->whereRaw('TIME(start_time) <= ?', [$time])
            ->whereRaw('TIME(end_time) >= ?', [$time])
            ->where('is_available', true)
            ->exists();

        if (!$isAvailable) {
            return back()->withInput()->with('error', 'Doctor is not available at this time. Please select another time slot.');
        }

        Appointment::create([
            'patient_id' => $validated['patient_id'],
            'doctor_id' => $validated['doctor_id'],
            'appointment_date' => $appointmentDateTime,
            'status' => 'scheduled',
            'notes' => $validated['notes'] ?? null,
            'is_paid' => $validated['is_paid'] ?? false,
        ]);

        return redirect()->route('receptionist.appointments.index')
            ->with('success', 'Appointment booked successfully.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor', 'medicalNotes']);
        return view('receptionist.appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $patients = Patient::orderBy('last_name')->orderBy('first_name')->get();
        $doctors = User::where('role', 'doctor')->orderBy('name')->get();
        
        return view('receptionist.appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string',
            'is_paid' => 'nullable|boolean',
        ]);

        $appointmentDateTime = Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);

        if ($appointment->appointment_date != $appointmentDateTime || $appointment->doctor_id != $validated['doctor_id']) {
            $existingAppointment = Appointment::where('doctor_id', $validated['doctor_id'])
                ->where('appointment_date', $appointmentDateTime)
                ->where('id', '!=', $appointment->id)
                ->where('status', '!=', 'cancelled')
                ->first();

            if ($existingAppointment) {
                return back()->withInput()->with('error', 'This time slot is already booked. Please choose another time.');
            }

            $dayOfWeek = strtolower($appointmentDateTime->format('l'));
            $time = $appointmentDateTime->format('H:i:s');
            
            $isAvailable = DoctorAvailability::where('doctor_id', $validated['doctor_id'])
                ->where('day_of_week', $dayOfWeek)
                ->whereRaw('TIME(start_time) <= ?', [$time])
                ->whereRaw('TIME(end_time) >= ?', [$time])
                ->where('is_available', true)
                ->exists();

            if (!$isAvailable) {
                return back()->withInput()->with('error', 'Doctor is not available at this time. Please select another time slot.');
            }
        }

        $appointment->update([
            'patient_id' => $validated['patient_id'],
            'doctor_id' => $validated['doctor_id'],
            'appointment_date' => $appointmentDateTime,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
            'is_paid' => $validated['is_paid'] ?? false,
        ]);

        return redirect()->route('receptionist.appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    public function cancel(Appointment $appointment)
    {
        if ($appointment->status === 'cancelled') {
            return back()->with('error', 'Appointment is already cancelled.');
        }

        $appointment->update(['status' => 'cancelled']);

        return redirect()->route('receptionist.appointments.index')
            ->with('success', 'Appointment cancelled successfully.');
    }

    public function availability(Request $request)
    {
        $doctors = User::where('role', 'doctor')->orderBy('name')->get();
        $selectedDoctor = null;
        $availabilities = collect();
        $date = $request->get('date', now()->format('Y-m-d'));

        if ($request->has('doctor_id')) {
            $selectedDoctor = User::find($request->doctor_id);
            if ($selectedDoctor) {
                $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));
                $availabilities = DoctorAvailability::where('doctor_id', $selectedDoctor->id)
                    ->where('day_of_week', $dayOfWeek)
                    ->where('is_available', true)
                    ->get();

                $bookedTimes = Appointment::where('doctor_id', $selectedDoctor->id)
                    ->whereDate('appointment_date', $date)
                    ->where('status', '!=', 'cancelled')
                    ->pluck('appointment_date')
                    ->map(function($dt) {
                        return Carbon::parse($dt)->format('H:i');
                    })
                    ->toArray();

                $availabilities = $availabilities->map(function($availability) use ($bookedTimes) {
                    $slots = [];
                    $start = Carbon::createFromTimeString($availability->start_time);
                    $end = Carbon::createFromTimeString($availability->end_time);
                    
                    $current = $start->copy();
                    while ($current < $end) {
                        $timeStr = $current->format('H:i');
                        if (!in_array($timeStr, $bookedTimes)) {
                            $slots[] = $timeStr;
                        }
                        $current->addMinutes(30);
                    }
                    
                    $availability->available_slots = $slots;
                    return $availability;
                });
            }
        }

        return view('receptionist.appointments.availability', compact('doctors', 'selectedDoctor', 'availabilities', 'date'));
    }

    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'date' => 'required|date',
        ]);

        $doctorId = $request->doctor_id;
        $date = $request->date;
        $dateCarbon = Carbon::parse($date);
        
        $dayOfWeek = strtolower($dateCarbon->format('l'));

        $availabilities = DoctorAvailability::where('doctor_id', $doctorId)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->get();

        $bookedTimes = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->where('status', '!=', 'cancelled')
            ->pluck('appointment_date')
            ->map(function($dt) {
                return Carbon::parse($dt)->format('H:i');
            })
            ->toArray();

        $availableSlots = [];
        foreach ($availabilities as $availability) {
            $start = Carbon::createFromTimeString($availability->start_time);
            $end = Carbon::createFromTimeString($availability->end_time);
            
            $current = $start->copy();
            while ($current < $end) {
                $timeStr = $current->format('H:i');
                if (!in_array($timeStr, $bookedTimes)) {
                    $availableSlots[] = $timeStr;
                }
                $current->addMinutes(30);
            }
        }

        sort($availableSlots);

        return response()->json([
            'slots' => $availableSlots,
            'date' => Carbon::parse($date)->format('F d, Y'),
        ]);
    }
}
