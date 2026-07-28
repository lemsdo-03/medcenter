<?php

namespace App\Http\Controllers\Pharmacist;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    //inventory list with search by name or code 
    public function index(Request $request)
    {
        $query = Medicine::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $medicines = $query->orderBy('name')->get();

        return view('pharmacist.medicines.index', compact('medicines'));
    }

    //add medicine form
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('pharmacist.medicines.create', compact('categories'));
    }

    //save new medicine
    public function store(Request $request)
    {
        $request->validate($this->rules());

        Medicine::create($request->only([
            'category_id', 'name', 'code', 'quantity', 'min_quantity', 'price', 'expiry_date',
        ]));

        return redirect()->route('pharmacist.medicines.index')->with('success', 'Medicine added successfully.');
    }

    //edit medicine 
    public function edit(Medicine $medicine)
    {
        $categories = Category::orderBy('name')->get();
        return view('pharmacist.medicines.edit', compact('medicine', 'categories'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $request->validate($this->rules($medicine));

        $medicine->update($request->only([
            'category_id', 'name', 'code', 'quantity', 'min_quantity', 'price', 'expiry_date',
        ]));

        return redirect()->route('pharmacist.medicines.index')->with('success', 'Medicine updated successfully.');
    }

    //validation shared by store and update
    private function rules(?Medicine $medicine = null): array
    {
        $uniqueCode = 'unique:medicines,code';
        if ($medicine) {
            $uniqueCode .= ',' . $medicine->id;
        }

        return [
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'code' => "required|string|max:100|{$uniqueCode}",
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'expiry_date' => 'nullable|date',
        ];
    }
}
