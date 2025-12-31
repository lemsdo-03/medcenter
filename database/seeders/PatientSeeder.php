<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'date_of_birth' => '1985-03-15',
                'gender' => 'male',
                'phone' => '555-0101',
                'email' => 'john.smith@email.com',
                'address' => '123 Main St, City, State 12345',
                'emergency_contact_name' => 'Jane Smith',
                'emergency_contact_phone' => '555-0102',
                'medical_history' => 'Hypertension, Type 2 Diabetes',
                'allergies' => 'Penicillin',
            ],
            [
                'first_name' => 'Maria',
                'last_name' => 'Garcia',
                'date_of_birth' => '1990-07-22',
                'gender' => 'female',
                'phone' => '555-0201',
                'email' => 'maria.garcia@email.com',
                'address' => '456 Oak Ave, City, State 12346',
                'emergency_contact_name' => 'Carlos Garcia',
                'emergency_contact_phone' => '555-0202',
                'medical_history' => 'Asthma',
                'allergies' => 'None',
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Brown',
                'date_of_birth' => '1978-11-08',
                'gender' => 'male',
                'phone' => '555-0301',
                'email' => 'david.brown@email.com',
                'address' => '789 Pine Rd, City, State 12347',
                'emergency_contact_name' => 'Susan Brown',
                'emergency_contact_phone' => '555-0302',
                'medical_history' => 'High cholesterol',
                'allergies' => 'Shellfish',
            ],
            [
                'first_name' => 'Jennifer',
                'last_name' => 'Lee',
                'date_of_birth' => '1992-05-14',
                'gender' => 'female',
                'phone' => '555-0401',
                'email' => 'jennifer.lee@email.com',
                'address' => '321 Elm St, City, State 12348',
                'emergency_contact_name' => 'Robert Lee',
                'emergency_contact_phone' => '555-0402',
                'medical_history' => 'Migraines',
                'allergies' => 'Latex',
            ],
            [
                'first_name' => 'Robert',
                'last_name' => 'Taylor',
                'date_of_birth' => '1982-09-30',
                'gender' => 'male',
                'phone' => '555-0501',
                'email' => 'robert.taylor@email.com',
                'address' => '654 Maple Dr, City, State 12349',
                'emergency_contact_name' => 'Patricia Taylor',
                'emergency_contact_phone' => '555-0502',
                'medical_history' => 'None',
                'allergies' => 'None',
            ],
            [
                'first_name' => 'Amanda',
                'last_name' => 'White',
                'date_of_birth' => '1988-12-05',
                'gender' => 'female',
                'phone' => '555-0601',
                'email' => 'amanda.white@email.com',
                'address' => '987 Cedar Ln, City, State 12350',
                'emergency_contact_name' => 'Thomas White',
                'emergency_contact_phone' => '555-0602',
                'medical_history' => 'Anxiety',
                'allergies' => 'Peanuts',
            ],
            [
                'first_name' => 'Christopher',
                'last_name' => 'Martinez',
                'date_of_birth' => '1995-01-18',
                'gender' => 'male',
                'phone' => '555-0701',
                'email' => 'chris.martinez@email.com',
                'address' => '147 Birch Way, City, State 12351',
                'emergency_contact_name' => 'Linda Martinez',
                'emergency_contact_phone' => '555-0702',
                'medical_history' => 'None',
                'allergies' => 'Dust mites',
            ],
            [
                'first_name' => 'Jessica',
                'last_name' => 'Anderson',
                'date_of_birth' => '1991-06-25',
                'gender' => 'female',
                'phone' => '555-0801',
                'email' => 'jessica.anderson@email.com',
                'address' => '258 Spruce St, City, State 12352',
                'emergency_contact_name' => 'Mark Anderson',
                'emergency_contact_phone' => '555-0802',
                'medical_history' => 'Hypothyroidism',
                'allergies' => 'None',
            ],
        ];

        foreach ($patients as $patient) {
            Patient::create($patient);
        }
    }
}
