<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function monthly(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        $appointments = Appointment::whereBetween('appointment_date', [$startDate, $endDate])
            ->with(['patient', 'doctor'])
            ->orderBy('appointment_date')
            ->get();

        $totalAppointments = $appointments->count();
        $paidAppointments = $appointments->where('is_paid', true)->count();
        $unpaidAppointments = $appointments->where('is_paid', false)->count();
        $completedAppointments = $appointments->where('status', 'completed')->count();
        $cancelledAppointments = $appointments->where('status', 'cancelled')->count();

        $appointmentsByDate = $appointments->groupBy(function($appointment) {
            return Carbon::parse($appointment->appointment_date)->format('Y-m-d');
        });

        $hasData = $appointments->count() > 0;

        return view('receptionist.reports.monthly', compact(
            'month',
            'appointments',
            'totalAppointments',
            'paidAppointments',
            'unpaidAppointments',
            'completedAppointments',
            'cancelledAppointments',
            'appointmentsByDate',
            'hasData'
        ));
    }
}
