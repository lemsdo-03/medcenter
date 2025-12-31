<?php

namespace Database\Seeders;

use App\Models\DoctorAvailability;
use App\Models\User;
use Illuminate\Database\Seeder;

class DoctorAvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        $doctors = User::where('role', 'doctor')->get();

        foreach ($doctors as $doctor) {
            // Monday to Friday: 9 AM to 5 PM
            $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
            foreach ($weekdays as $day) {
                DoctorAvailability::create([
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'is_available' => true,
                ]);
            }

            // Saturday: 9 AM to 1 PM (only for first doctor)
            if ($doctor->id === $doctors->first()->id) {
                DoctorAvailability::create([
                    'doctor_id' => $doctor->id,
                    'day_of_week' => 'saturday',
                    'start_time' => '09:00:00',
                    'end_time' => '13:00:00',
                    'is_available' => true,
                ]);
            }
        }
    }
}
