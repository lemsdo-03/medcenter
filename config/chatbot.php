<?php

// Static content used by the patient chatbot (FR-52..FR-61).
// Edit the values here to change what the assistant replies.

return [

    // FR-54: working hours
    'working_hours' => [
        'days' => 'Sunday – Thursday',
        'open' => '09:00',
        'close' => '17:00',
        'text' => 'We are open Sunday to Thursday, 9:00 AM to 5:00 PM. We are closed on Friday and Saturday.',
    ],

    // FR-55: center location
    'location' => [
        'address' => 'MedCenter, King Fahd Road, Riyadh, Saudi Arabia',
        'map_link' => 'https://maps.google.com/?q=MedCenter+Riyadh',
    ],

    // FR-57: appointment booking steps
    'booking_steps' => [
        'Open the "Doctors" page from the top menu.',
        'Choose a doctor and click "View".',
        'Pick an available date and time slot.',
        'Confirm your booking — it will appear under "Appointments".',
    ],

    // FR-53: frequently asked questions
    'faqs' => [
        ['q' => 'How do I cancel an appointment?', 'a' => 'Go to the "Appointments" page, open the appointment, and click Cancel.'],
        ['q' => 'How do I update my profile?', 'a' => 'Open the menu in the top right corner and click "Profile".'],
        ['q' => 'Do I need to pay online?', 'a' => 'No. Payment is handled at the reception desk during your visit.'],
        ['q' => 'Can I chat with my doctor?', 'a' => 'Yes. Use the "Chat" page to message your doctor directly.'],
    ],

    // FR-60: downloadable files and instructions
    'downloads' => [
        ['name' => 'New Patient Form', 'url' => '#', 'instructions' => 'Download, fill it in, and bring it to reception on your first visit.'],
        ['name' => 'Insurance Claim Form', 'url' => '#', 'instructions' => 'Complete after your visit and submit it to reception.'],
    ],
];
