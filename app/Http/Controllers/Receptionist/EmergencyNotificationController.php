<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\EmergencyNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmergencyNotificationController extends Controller
{
    public function create()
    {
        $doctors = User::where('role', 'doctor')->orderBy('name')->get();

        return view('receptionist.emergency.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'message' => 'required|string|max:2000',
        ]);

        EmergencyNotification::create([
            'doctor_id' => $validated['doctor_id'],
            'receptionist_id' => Auth::id(),
            'message' => $validated['message'],
        ]);

        return redirect()->route('receptionist.appointments.index')
            ->with('success', 'Emergency notification sent to doctor.');
    }
}


