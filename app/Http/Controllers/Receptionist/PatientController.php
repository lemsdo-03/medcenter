<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%");
            });
        }

        $patients = $query->orderBy('last_name')->orderBy('first_name')->paginate(15);

        return view('receptionist.patients.index', compact('patients'));
    }

    public function create()
    {
        $doctors = \App\Models\User::where('role', 'doctor')->orderBy('name')->get();
        return view('receptionist.patients.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'medical_history' => 'nullable|string',
            'allergies' => 'nullable|string',
            'book_appointment' => 'nullable|boolean',
            'appointment_notes' => 'nullable|string',
        ];

        if ($request->has('book_appointment') && $request->book_appointment) {
            $rules['doctor_id'] = 'required|exists:users,id';
            $rules['appointment_date'] = 'required|date';
            $rules['appointment_time'] = 'required|date_format:H:i';
        } else {
            $rules['doctor_id'] = 'nullable|exists:users,id';
            $rules['appointment_date'] = 'nullable|date';
            $rules['appointment_time'] = 'nullable|date_format:H:i';
        }

        $validated = $request->validate($rules);

        $duplicate = Patient::where('first_name', $validated['first_name'])
            ->where('last_name', $validated['last_name'])
            ->where('date_of_birth', $validated['date_of_birth'])
            ->first();

        if ($duplicate) {
            return back()->withInput()->with('warning', 'A patient with the same name and date of birth already exists. Please verify if this is a duplicate.');
        }

        $patient = Patient::create($validated);

        if ($request->has('book_appointment') && $request->book_appointment && 
            $request->filled('doctor_id') && $request->filled('appointment_date') && $request->filled('appointment_time')) {
            
            $appointmentDateTime = \Carbon\Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);

            if ($appointmentDateTime->isPast()) {
                return back()->withInput()->with('error', 'You cannot book an appointment in the past.');
            }
            
            $existingAppointment = \App\Models\Appointment::where('doctor_id', $validated['doctor_id'])
                ->where('appointment_date', $appointmentDateTime)
                ->where('status', '!=', 'cancelled')
                ->first();

            if (!$existingAppointment) {
                $dayOfWeek = strtolower($appointmentDateTime->format('l'));
                $time = $appointmentDateTime->format('H:i:s');
                
                $isAvailable = \App\Models\DoctorAvailability::where('doctor_id', $validated['doctor_id'])
                    ->where('day_of_week', $dayOfWeek)
                    ->whereRaw('TIME(start_time) <= ?', [$time])
                    ->whereRaw('TIME(end_time) >= ?', [$time])
                    ->where('is_available', true)
                    ->exists();

                if ($isAvailable) {
                    \App\Models\Appointment::create([
                        'patient_id' => $patient->id,
                        'doctor_id' => $validated['doctor_id'],
                        'appointment_date' => $appointmentDateTime,
                        'status' => 'scheduled',
                        'notes' => $validated['appointment_notes'] ?? null,
                    ]);
                    
                    return redirect()->route('receptionist.patients.index')
                        ->with('success', 'Patient registered and appointment booked successfully.');
                }
            }
        }

        return redirect()->route('receptionist.patients.index')
            ->with('success', 'Patient registered successfully.');
    }

    public function show(Patient $patient)
    {
$patient->load(['appointments.doctor', 'medicalNotes.doctor']);

        return view('receptionist.patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('receptionist.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'medical_history' => 'nullable|string',
            'allergies' => 'nullable|string',
        ]);

        $patient->update($validated);

        return redirect()->route('receptionist.patients.index')
            ->with('success', 'Patient information updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        if ($patient->appointments()->count() > 0) {
            return back()->with('error', 'Cannot delete patient with existing appointments.');
        }

        $patient->delete();

        return redirect()->route('receptionist.patients.index')
            ->with('success', 'Patient deleted successfully.');
    }
}
