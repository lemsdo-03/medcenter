<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Conversation;

class DashboardController extends Controller
{
    public function index()
    {
        //find the patient row that belongs to the logged in user

        $patient = Patient::where('user_id', auth()->id())->firstOrFail();

        //next 5 upcoming appointments 
    
        $upcomingAppointments = $patient->appointments()
            ->with('doctor')
            ->where('status', 'scheduled')
            ->where('appointment_date', '>=', now())
            ->orderBy('appointment_date')
            ->take(5)
            ->get();

        //last 5 notifications
        $recentNotifications = $patient->notifications()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        //just countnotifications i didnt read 
        $unreadNotifications = $patient->notifications()
            ->where('is_read', false)
            ->count();

        $unreadMessages = Conversation::where('patient_id', $patient->id)
            ->withCount(['messages as unread_count' => function ($q) {
                $q->where('sender_type', 'doctor')
                  ->where('created_at', '>', now()->subDay());
            }])
            ->get()
            ->sum('unread_count');

       
        return view('patient.dashboard', compact(
            'patient',
            'upcomingAppointments',
            'recentNotifications',
            'unreadNotifications',
            'unreadMessages'
        ));
    }
}
