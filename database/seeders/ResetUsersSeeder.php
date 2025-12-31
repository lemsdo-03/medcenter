<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class ResetUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure FK constraints don't block truncation
        Schema::disableForeignKeyConstraints();
        // Truncate users; cascades will clean related rows where defined
        User::query()->delete();
        Schema::enableForeignKeyConstraints();

        // 1 Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@medcenter.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 3 Doctors
        User::create([
            'name' => 'Dr. Alice Nguyen',
            'email' => 'alice.nguyen@medcenter.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
        ]);
        User::create([
            'name' => 'Dr. Omar Hassan',
            'email' => 'omar.hassan@medcenter.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
        ]);
        User::create([
            'name' => 'Dr. Priya Patel',
            'email' => 'priya.patel@medcenter.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
        ]);

        // 2 Receptionists
        User::create([
            'name' => 'Rachel Green',
            'email' => 'rachel.green@medcenter.com',
            'password' => Hash::make('password'),
            'role' => 'receptionist',
        ]);
        User::create([
            'name' => 'Tom Baker',
            'email' => 'tom.baker@medcenter.com',
            'password' => Hash::make('password'),
            'role' => 'receptionist',
        ]);
    }
}

