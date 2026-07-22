<?php

namespace App\Http\Controllers\Pharmacist;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //list all categories with how many medicines each holds
    public function index()
    {
        $categories = Category::withCount('medicines')->orderBy('name')->get();
        return view('pharmacist.categories.index', compact('categories'));
    }

    //show the add category form (FR-37)
    public function create()
    {
        return view('pharmacist.categories.create');
    }

    //save a new category (FR-37: name must be unique)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create(['name' => $request->name]);

        return redirect()->route('pharmacist.categories.index')->with('success', 'Category added successfully.');
    }

    //edit category name
    public function edit(Category $category)
    {
        return view('pharmacist.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update(['name' => $request->name]);

        return redirect()->route('pharmacist.categories.index')->with('success', 'Category updated successfully.');
    }

    //delete a category (FR-39: cannot delete if it still contains medicines)
    public function destroy(Category $category)
    {
        if ($category->medicines()->count() > 0) {
            return back()->with('error', 'Cannot delete a category that still contains medicines.');
        }

        $category->delete();

        return redirect()->route('pharmacist.categories.index')->with('success', 'Category deleted successfully.');
    }
}
