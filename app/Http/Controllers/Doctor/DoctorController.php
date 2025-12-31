<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\MedicalNote;
use App\Models\DoctorAvailability;
use App\Models\EmergencyNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DoctorController extends Controller
{
    private function markPastAppointmentsAsCompleted($doctorId)
    {
        Appointment::where('doctor_id', $doctorId)
            ->where('status', 'scheduled')
            ->where('appointment_date', '<', now())
            ->update(['status' => 'completed']);
    }

    public function dashboard()
    {
        $doctor = Auth::user();
        $this->markPastAppointmentsAsCompleted($doctor->id);

        // Fetch latest emergency notification for this doctor
        $latestEmergencyRecord = EmergencyNotification::where('doctor_id', $doctor->id)
            ->latest()
            ->first();

        // Only show it once per session: if we've already seen this ID, don't show again
        $latestEmergency = null;
        if ($latestEmergencyRecord) {
            $lastSeenId = session('last_seen_emergency_id');
            if ($lastSeenId !== $latestEmergencyRecord->id) {
                $latestEmergency = $latestEmergencyRecord;
                session(['last_seen_emergency_id' => $latestEmergencyRecord->id]);
            }
        }
        
        $todayAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today())
            ->where('status', '!=', 'cancelled')
            ->with('patient')
            ->orderBy('appointment_date')
            ->get();

        $upcomingAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', '>', today())
            ->where('status', '!=', 'cancelled')
            ->with('patient')
            ->orderBy('appointment_date')
            ->limit(10)
            ->get();

        return view('doctor.dashboard', compact('todayAppointments', 'upcomingAppointments', 'latestEmergency'));
    }

    public function latestEmergency()
    {
        $doctor = Auth::user();
        $notification = EmergencyNotification::where('doctor_id', $doctor->id)
            ->latest()
            ->first();

        // If there is no notification or we've already seen this one in this session, return null
        if (!$notification || session('last_seen_emergency_id') === $notification->id) {
            return response()->json(['notification' => null]);
        }

        // Mark as seen for this session so it doesn't keep popping up
        session(['last_seen_emergency_id' => $notification->id]);

        return response()->json([
            'notification' => [
                'id' => $notification->id,
                'message' => $notification->message,
                'created_at' => $notification->created_at->toDateTimeString(),
            ],
        ]);
    }

    public function appointments(Request $request)
    {
        $doctor = Auth::user();
        $this->markPastAppointmentsAsCompleted($doctor->id);
        
        $query = Appointment::where('doctor_id', $doctor->id)
            ->with('patient');

        if ($request->has('date')) {
            $date = Carbon::parse($request->date);
            $query->whereDate('appointment_date', $date);
        } else {
            if (!$request->has('status') || $request->status !== 'completed') {
                $query->whereDate('appointment_date', '>=', today());
            }
        }

        if ($request->has('status')) {
            if ($request->status === 'past') {
                $query->where('appointment_date', '<', now());
            } else {
                $query->where('status', $request->status);
            }
        }

        $appointments = $query->orderBy('appointment_date', 'desc')->paginate(20);
        $hasAppointments = $appointments->count() > 0;

        return view('doctor.appointments', compact('appointments', 'hasAppointments'));
    }

    public function showAppointment($id)
    {
        $doctor = Auth::user();
        $this->markPastAppointmentsAsCompleted($doctor->id);
        
        $appointment = Appointment::where('doctor_id', $doctor->id)
            ->with(['patient', 'medicalNotes'])
            ->findOrFail($id);
        
        $appointment->refresh();

        return view('doctor.appointment-show', compact('appointment'));
    }

    public function viewPatient(Patient $patient)
    {
       $patient->load(['appointments.doctor', 'medicalNotes.doctor']);

        return view('doctor.patient-view', compact('patient'));
    }

    public function availability()
    {
        $doctor = Auth::user();
        $availabilities = DoctorAvailability::where('doctor_id', $doctor->id)
            ->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')")
            ->orderBy('start_time')
            ->get();

        return view('doctor.availability', compact('availabilities'));
    }

    public function storeAvailability(Request $request)
    {
        $doctor = Auth::user();

        $validated = $request->validate([
            'availabilities' => 'required|array',
            'availabilities.*.day_of_week' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'availabilities.*.start_time' => 'required|date_format:H:i',
            'availabilities.*.end_time' => 'required|date_format:H:i|after:availabilities.*.start_time',
            'availabilities.*.is_available' => 'boolean',
        ]);

        DoctorAvailability::where('doctor_id', $doctor->id)->delete();

        foreach ($validated['availabilities'] as $availability) {
            DoctorAvailability::create([
                'doctor_id' => $doctor->id,
                'day_of_week' => $availability['day_of_week'],
                'start_time' => $availability['start_time'],
                'end_time' => $availability['end_time'],
                'is_available' => $availability['is_available'] ?? true,
            ]);
        }

        return redirect()->route('doctor.availability')
            ->with('success', 'Availability schedule updated successfully.');
    }

    public function addMedicalNote(Request $request, Patient $patient, Appointment $appointment = null)
    {
        $doctor = Auth::user();
        
        if ($appointment && $appointment->doctor_id !== $doctor->id) {
            abort(403, 'Unauthorized');
        }

        $appointments = Appointment::where('patient_id', $patient->id)
            ->where('doctor_id', $doctor->id)
            ->orderBy('appointment_date', 'desc')
            ->get();

        return view('doctor.add-medical-note', compact('patient', 'appointment', 'appointments'));
    }

    public function storeMedicalNote(Request $request, Patient $patient)
    {
        $doctor = Auth::user();

        $validated = $request->validate([
            'appointment_id' => 'nullable|exists:appointments,id',
            'notes' => 'required|string',
            'diagnosis' => 'nullable|string|max:255',
            'prescription' => 'nullable|string',
        ]);

        if ($validated['appointment_id']) {
            $appointment = Appointment::findOrFail($validated['appointment_id']);
            if ($appointment->doctor_id !== $doctor->id) {
                abort(403, 'Unauthorized');
            }
        }

        MedicalNote::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_id' => $validated['appointment_id'] ?? null,
            'notes' => $validated['notes'],
            'diagnosis' => $validated['diagnosis'] ?? null,
            'prescription' => $validated['prescription'] ?? null,
        ]);

        return redirect()->route('doctor.patient.view', $patient)
            ->with('success', 'Medical notes added successfully.');
    }
}
