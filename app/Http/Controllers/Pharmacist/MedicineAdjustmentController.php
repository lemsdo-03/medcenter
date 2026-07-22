<?php

namespace App\Http\Controllers\Pharmacist;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\MedicineAdjustment;
use Illuminate\Http\Request;

class MedicineAdjustmentController extends Controller
{
    //FR-49: list every damaged / returned record
    public function index()
    {
        $adjustments = MedicineAdjustment::with(['medicine', 'pharmacist'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('pharmacist.adjustments.index', compact('adjustments'));
    }

    //show the record form (a medicine can be pre-selected from its row)
    public function create(Request $request)
    {
        $medicines = Medicine::orderBy('name')->get();
        $selected = $request->medicine; // optional pre-selected medicine id

        return view('pharmacist.adjustments.create', compact('medicines', 'selected'));
    }

    //FR-49: store the record and reduce stock (alt scenario: invalid quantity)
    public function store(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'type' => 'required|in:damaged,returned',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        $medicine = Medicine::findOrFail($request->medicine_id);

        // cannot write off more than what is in stock
        if ($request->quantity > $medicine->quantity) {
            return back()->withInput()->with('error', "Invalid quantity. Only {$medicine->quantity} of {$medicine->name} in stock.");
        }

        MedicineAdjustment::create([
            'medicine_id' => $medicine->id,
            'pharmacist_id' => auth()->id(),
            'type' => $request->type,
            'quantity' => $request->quantity,
            'reason' => $request->reason,
        ]);

        // remove the damaged / returned units from sellable stock
        $medicine->decrement('quantity', $request->quantity);

        return redirect()->route('pharmacist.adjustments.index')->with('success', 'Record saved and stock updated.');
    }
}
