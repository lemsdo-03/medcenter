<?php

namespace App\Http\Controllers\Pharmacist;

use App\Http\Controllers\Controller;
use App\Models\Dispense;
use App\Models\Medicine;
use App\Models\MedicalNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DispenseController extends Controller
{
    //FR-43: open the dispense form for a prescription (pick which medicines + quantities to hand out)
    public function create(MedicalNote $prescription)
    {
        // a prescription can only be dispensed once
        if ($prescription->dispense) {
            return redirect()->route('pharmacist.dispenses.invoice', $prescription->dispense)
                ->with('error', 'This prescription has already been dispensed.');
        }

        $prescription->load(['patient', 'doctor']);
        $medicines = Medicine::orderBy('name')->get();

        return view('pharmacist.dispenses.create', compact('prescription', 'medicines'));
    }

    //FR-43: process the dispense, decrease stock, build the invoice
    public function store(Request $request, MedicalNote $prescription)
    {
        if ($prescription->dispense) {
            return redirect()->route('pharmacist.dispenses.invoice', $prescription->dispense)
                ->with('error', 'This prescription has already been dispensed.');
        }

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $medicines = Medicine::whereIn('id', collect($request->items)->pluck('medicine_id'))->get()->keyBy('id');

        // FR-43 alternative scenario: medicine unavailable / not enough stock
        foreach ($request->items as $item) {
            $medicine = $medicines[$item['medicine_id']];
            if ($medicine->quantity < $item['quantity']) {
                return back()->withInput()->with('error', "Not enough stock for {$medicine->name} (only {$medicine->quantity} left).");
            }
        }

        $dispense = DB::transaction(function () use ($request, $prescription, $medicines) {
            $dispense = Dispense::create([
                'patient_id' => $prescription->patient_id,
                'medical_note_id' => $prescription->id,
                'pharmacist_id' => auth()->id(),
                'total' => 0,
            ]);

            $total = 0;

            foreach ($request->items as $item) {
                $medicine = $medicines[$item['medicine_id']];
                $qty = (int) $item['quantity'];
                $lineTotal = $medicine->price * $qty;
                $total += $lineTotal;

                $dispense->items()->create([
                    'medicine_id' => $medicine->id,
                    'medicine_name' => $medicine->name,
                    'quantity' => $qty,
                    'unit_price' => $medicine->price,
                    'line_total' => $lineTotal,
                ]);

                // decrease stock
                $medicine->decrement('quantity', $qty);
            }

            $dispense->update(['total' => $total]);

            return $dispense;
        });

        return redirect()->route('pharmacist.dispenses.invoice', $dispense)
            ->with('success', 'Medicine dispensed successfully.');
    }

    //FR-48: printable invoice for a dispense
    public function invoice(Dispense $dispense)
    {
        $dispense->load(['patient', 'pharmacist', 'items', 'medicalNote.doctor']);
        return view('pharmacist.dispenses.invoice', compact('dispense'));
    }
}
