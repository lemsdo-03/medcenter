<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@medcenter.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Doctors
        $doctors = [
            ['name' => 'Dr. Sarah Johnson', 'email' => 'sarah.johnson@medcenter.com'],
            ['name' => 'Dr. Michael Chen', 'email' => 'michael.chen@medcenter.com'],
            ['name' => 'Dr. Emily Rodriguez', 'email' => 'emily.rodriguez@medcenter.com'],
        ];

        foreach ($doctors as $doctor) {
            User::create([
                'name' => $doctor['name'],
                'email' => $doctor['email'],
                'password' => Hash::make('password'),
                'role' => 'doctor',
            ]);
        }

        // Receptionists
        $receptionists = [
            ['name' => 'Lisa Anderson', 'email' => 'lisa.anderson@medcenter.com'],
            ['name' => 'James Wilson', 'email' => 'james.wilson@medcenter.com'],
        ];

        foreach ($receptionists as $receptionist) {
            User::create([
                'name' => $receptionist['name'],
                'email' => $receptionist['email'],
                'password' => Hash::make('password'),
                'role' => 'receptionist',
            ]);
        }
    }
}
