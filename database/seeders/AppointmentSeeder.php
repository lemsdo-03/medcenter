<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = User::where('role', 'doctor')->get();
        $patients = Patient::all();

        if ($doctors->isEmpty() || $patients->isEmpty()) {
            return;
        }

        // Create appointments for the next 2 weeks
        $startDate = Carbon::now()->startOfDay()->addDay();
        
        for ($day = 0; $day < 14; $day++) {
            $date = $startDate->copy()->addDays($day);
            $dayOfWeek = strtolower($date->format('l'));

            // Skip Sundays
            if ($dayOfWeek === 'sunday') {
                continue;
            }

            foreach ($doctors as $doctor) {
                // Create 2-4 appointments per doctor per day
                $appointmentCount = rand(2, 4);
                
                for ($i = 0; $i < $appointmentCount; $i++) {
                    $hour = 9 + ($i * 2); // 9 AM, 11 AM, 1 PM, 3 PM
                    $appointmentDate = $date->copy()->setTime($hour, 0);

                    // Random patient
                    $patient = $patients->random();

                    // Random status (mostly scheduled, some completed for past dates)
                    $status = $appointmentDate->isPast() 
                        ? (rand(0, 1) ? 'completed' : 'scheduled')
                        : 'scheduled';

                    Appointment::create([
                        'patient_id' => $patient->id,
                        'doctor_id' => $doctor->id,
                        'appointment_date' => $appointmentDate,
                        'status' => $status,
                        'notes' => rand(0, 1) ? 'Regular checkup' : null,
                    ]);
                }
            }
        }
    }
}
