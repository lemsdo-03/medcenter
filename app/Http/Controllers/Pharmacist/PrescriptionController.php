<?php

namespace App\Http\Controllers\Pharmacist;

use App\Http\Controllers\Controller;
use App\Models\MedicalNote;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    //FR-42: prescriptions received from doctors (medical notes that contain a prescription)
    public function index(Request $request)
    {
        $query = MedicalNote::with(['patient', 'doctor', 'dispense'])
            ->whereNotNull('prescription')
            ->where('prescription', '!=', '');

        // optional filter: pending vs dispensed
        if ($request->status === 'pending') {
            $query->whereDoesntHave('dispense');
        } elseif ($request->status === 'dispensed') {
            $query->whereHas('dispense');
        }

        $prescriptions = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('pharmacist.prescriptions.index', compact('prescriptions'));
    }

    //FR-47: view the prescription with its owner (patient) information
    public function show(MedicalNote $prescription)
    {
        $prescription->load(['patient', 'doctor', 'dispense.items']);
        return view('pharmacist.prescriptions.show', compact('prescription'));
    }
}
