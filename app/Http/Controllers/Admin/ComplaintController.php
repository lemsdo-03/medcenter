<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = Rating::with(['patient', 'doctor', 'appointment']);

        if ($request->has('type') && in_array($request->type, ['rating', 'complaint'])) {
            $query->where('type', $request->type);
        }

        $ratings = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.complaints.index', compact('ratings'));
    }

    public function show(Rating $rating)
    {
        $rating->load(['patient', 'doctor', 'appointment']);
        return view('admin.complaints.show', compact('rating'));
    }
}
