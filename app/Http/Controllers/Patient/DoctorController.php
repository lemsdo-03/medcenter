<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DoctorAvailability;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DoctorController extends Controller
{
    //list of all doctors 
    public function index()
    {
        $doctors = User::where('role', 'doctor')->orderBy('name')->get();
        return view('patient.doctors.index', compact('doctors'));
    }

    //show one doctor's profile and weekly schedule
    public function show(User $doctor)
    {
        //if someone types other than dr in the url abort
        if ($doctor->role !== 'doctor') {
            abort(404);
        }

        //get this doctor's available 
        $availabilities = DoctorAvailability::where('doctor_id', $doctor->id)
            ->where('is_available', true)
            ->orderByRaw("FIELD(day_of_week, 'monday','tuesday','wednesday','thursday','friday','saturday','sunday')")
            ->get();

        return view('patient.doctors.show', compact('doctor', 'availabilities'));
    }

    
    public function slots(Request $request, User $doctor)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        //figure out the weekday name (monday, tuesday...) from the picked date
        $date = $request->date;
        $dateCarbon = Carbon::parse($date);
        $dayOfWeek = strtolower($dateCarbon->format('l')); // 

        //the doctor's working windows for that weekday (e.g. 09:00-12:00, 14:00-17:00)
        $availabilities = DoctorAvailability::where('doctor_id', $doctor->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->get();

        //all the times already booked on this date (so we skip them)

        $bookedTimes = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $date)
            ->where('status', '!=', 'cancelled')
            ->pluck('appointment_date')
            ->map(fn($dt) => Carbon::parse($dt)->format('H:i'))
            ->toArray();

        //walk each working window in 30-min steps, skip ones already booked
        $availableSlots = [];
        foreach ($availabilities as $availability) {
            $start = Carbon::createFromTimeString($availability->start_time);
            $end = Carbon::createFromTimeString($availability->end_time);

            $current = $start->copy();
            while ($current < $end) {
                $timeStr = $current->format('H:i');
                if (!in_array($timeStr, $bookedTimes)) {
                    $availableSlots[] = $timeStr; //free, add it
                }
                $current->addMinutes(30); //jump 30 mins forward
            }
        }

        sort($availableSlots); 

      
        return response()->json([
            'slots' => $availableSlots,
            'date' => $dateCarbon->format('F d, Y'),
        ]);
    }
}
