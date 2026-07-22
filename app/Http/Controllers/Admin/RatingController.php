<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    //FR-69: shows ONLY ratings (managed separately from complaints)
    public function index()
    {
        $ratings = Rating::with(['patient', 'doctor', 'appointment'])
            ->where('type', 'rating')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.ratings.index', compact('ratings'));
    }

    //shows one rating in detail
    public function show(Rating $rating)
    {
        $rating->load(['patient', 'doctor', 'appointment']);
        return view('admin.ratings.show', compact('rating'));
    }
}
