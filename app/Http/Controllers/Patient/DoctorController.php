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
    public function index()
    {
        $doctors = User::where('role', 'doctor')->orderBy('name')->get();
        return view('patient.doctors.index', compact('doctors'));
    }

    public function show(User $doctor)
    {
        if ($doctor->role !== 'doctor') {
            abort(404);
        }

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

        $date = $request->date;
        $dateCarbon = Carbon::parse($date);
        $dayOfWeek = strtolower($dateCarbon->format('l'));

        $availabilities = DoctorAvailability::where('doctor_id', $doctor->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->get();

        $bookedTimes = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $date)
            ->where('status', '!=', 'cancelled')
            ->pluck('appointment_date')
            ->map(fn($dt) => Carbon::parse($dt)->format('H:i'))
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
            'date' => $dateCarbon->format('F d, Y'),
        ]);
    }
}
