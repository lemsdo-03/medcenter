<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $patient = Patient::where('user_id', auth()->id())->firstOrFail();

        $notifications = $patient->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('patient.notifications.index', compact('notifications'));
    }

    public function markAsRead(PatientNotification $notification)
    {
        $patient = Patient::where('user_id', auth()->id())->firstOrFail();

        if ($notification->patient_id !== $patient->id) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        return back()->with('success', 'Notification marked as read.');
    }
}
