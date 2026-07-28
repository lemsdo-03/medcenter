<?php

namespace App\Http\Controllers\Pharmacist;

use App\Http\Controllers\Controller;
use App\Models\Dispense;
use App\Models\DispenseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    //daily sales record 
    public function daily(Request $request)
    {
        $date = $request->filled('date') ? Carbon::parse($request->date) : today();

        $dispenses = Dispense::with(['patient', 'items'])
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'desc')
            ->get();

        $total = $dispenses->sum('total');

        return view('pharmacist.reports.daily', compact('dispenses', 'total', 'date'));
    }

    //monthly most requested medicines 
    public function monthly(Request $request)
    {
        // month input format: YYYY-MM
        $month = $request->filled('month') ? Carbon::parse($request->month . '-01') : today()->startOfMonth();

        $medicines = DispenseItem::select(
                'medicine_name',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(line_total) as total_sales')
            )
            ->whereYear('created_at', $month->year)
            ->whereMonth('created_at', $month->month)
            ->groupBy('medicine_name')
            ->orderByDesc('total_quantity')
            ->get();

        return view('pharmacist.reports.monthly', compact('medicines', 'month'));
    }
}
