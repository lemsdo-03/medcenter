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
//handles the doctor side: shows the dashboard + appointments list/details, polls emergency alerts, manages availability schedule, and lets the doctor view patients and add/store medical notes.
class DoctorController extends Controller
{
    //
    private function markPastAppointmentsAsCompleted($doctorId)
    {
        Appointment::where('doctor_id', $doctorId) //finding the appointment witht the dr id 
            ->where('status', 'scheduled')//that are still scudeuled 
            ->where('appointment_date', '<', now())//but the date has passed already
            ->update(['status' => 'completed']);//change it to completed
    }

    public function dashboard()
    {
        $doctor = Auth::user(); //logged in as dr
        $this->markPastAppointmentsAsCompleted($doctor->id);//do what that function dr

        
        
        $todayAppointments = Appointment::where('doctor_id', $doctor->id) //only appointment for this dr id
            ->whereDate('appointment_date', today())
            ->where('status', '!=', 'cancelled') //exclude cancelled 
            ->with('patient') //patient info
            ->orderBy('appointment_date')
            ->get();

        $upcomingAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', '>', today())
            ->where('status', '!=', 'cancelled')
            ->with('patient')
            ->orderBy('appointment_date')
            ->limit(10) //shows 10 appointments only
            ->get();

        return view('doctor.dashboard', compact('todayAppointments', 'upcomingAppointments')); //show in view for that dr
    }

    public function latestEmergency()
    {
        $doctor = Auth::user(); //user is dr
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
        
        $query = Appointment::where('doctor_id', $doctor->id)//for this id only
            ->with('patient'); //loads info for patient

        if ($request->has('date')) { //if i wrote a specific date 
            $date = Carbon::parse($request->date);//show that date only
            $query->whereDate('appointment_date', $date);
        } else {
            if (!$request->has('status') || $request->status !== 'completed') {
                $query->whereDate('appointment_date', '>=', today());
            }
        }

        if ($request->has('status')) {
            if ($request->status === 'past') {
                $query->where('appointment_date', '<', now());//past appointment filter by them 
            } else {
                $query->where('status', $request->status);//completed..canceled,..
            }
        }

        $appointments = $query->orderBy('appointment_date', 'desc')->paginate(20);  //sort newsest first and show 20 per page
        $hasAppointments = $appointments->count() > 0; //the no appointment 

        return view('doctor.appointments', compact('appointments', 'hasAppointments'));
    }

    public function showAppointment($id)
    {
        $doctor = Auth::user();
        $this->markPastAppointmentsAsCompleted($doctor->id);
        
        $appointment = Appointment::where('doctor_id', $doctor->id)//show appointment by dr id onlyy
            ->with(['patient', 'medicalNotes'])//load patient and medical notes on the page
            ->findOrFail($id);//show error if not found
        
        $appointment->refresh(); //refresh db

        return view('doctor.appointment-show', compact('appointment'));
    }

    public function viewPatient(Patient $patient)
    {
       $patient->load(['appointments.doctor', 'medicalNotes.doctor']);

        return view('doctor.patient-view', compact('patient'));
    }

    public function availability()
    {
        $doctor = Auth::user();//make sure it's logged as dr
        $availabilities = DoctorAvailability::where('doctor_id', $doctor->id)//loads dr avail
            ->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')")//order
            ->orderBy('start_time')
            ->get();

        return view('doctor.availability', compact('availabilities'));
    }

    public function storeAvailability(Request $request)
    {
        $doctor = Auth::user();//makes sure it's dr

        $validated = $request->validate([
            'availabilities' => 'required|array',//an array that have day of week start time endtime and is available 
            'availabilities.*.day_of_week' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'availabilities.*.start_time' => 'required|date_format:H:i',
            'availabilities.*.end_time' => 'required|date_format:H:i|after:availabilities.*.start_time',
            'availabilities.*.is_available' => 'boolean',
        ]);

        DoctorAvailability::where('doctor_id', $doctor->id)->delete();//delete the old schedle

        foreach ($validated['availabilities'] as $availability) { //icreates rows 
            DoctorAvailability::create([
                'doctor_id' => $doctor->id,
                'day_of_week' => $availability['day_of_week'],
                'start_time' => $availability['start_time'],
                'end_time' => $availability['end_time'],
                'is_available' => $availability['is_available'] ?? true, //if not wrote it's true by default
            ]);
        }

        return redirect()->route('doctor.availability')
            ->with('success', 'Availability schedule updated successfully.'); //send a messege 
    }

    public function addMedicalNote(Request $request, Patient $patient, Appointment $appointment = null)
    {
        $doctor = Auth::user();
        
        if ($appointment && $appointment->doctor_id !== $doctor->id) {
            abort(403, 'Unauthorized');//if someone try to add a note to not their patient
        }

        $appointments = Appointment::where('patient_id', $patient->id)//gets the patient and dr right record to add a note
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
