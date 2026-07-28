<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
  
    public function index()
    {
        $complaints = Rating::with(['patient', 'doctor', 'appointment'])
            ->where('type', 'complaint')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.complaints.index', compact('complaints'));
    }

    //shows one complaint in detail
    public function show(Rating $rating)
    {
        $rating->load(['patient', 'doctor', 'appointment']);
        return view('admin.complaints.show', compact('rating'));
    }
}
