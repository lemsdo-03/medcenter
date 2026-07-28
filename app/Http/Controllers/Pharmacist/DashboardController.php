<?php

namespace App\Http\Controllers\Pharmacist;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Dispense;
use App\Models\Medicine;
use App\Models\MedicalNote;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //pharmacist home: quick stats + the low-stock alert 
    public function index()
    {
        $stats = [
            'medicines' => Medicine::count(),
            'categories' => Category::count(),
            // pending prescriptions = medical notes that have a prescription but were not dispensed yet 
            'pending' => MedicalNote::whereNotNull('prescription')
                ->where('prescription', '!=', '')
                ->whereDoesntHave('dispense')
                ->count(),
            // today's sales total 
            'todaySales' => Dispense::whereDate('created_at', today())->sum('total'),
        ];

        // FR-44: medicines at or below their minimum threshold
        $lowStock = Medicine::with('category')
            ->whereColumn('quantity', '<=', 'min_quantity')
            ->orderBy('quantity')
            ->get();

        return view('pharmacist.dashboard', compact('stats', 'lowStock'));
    }
}
