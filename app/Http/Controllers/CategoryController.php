<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        if (request()->wantsJson()) {
            return response()->json($categories);
        }
        return back();
    }

    public function show(Category $category)
    {
        return response()->json($category);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string',
            'sla_days' => 'required|integer|min:1',
            'severity' => 'required|string|in:Low,Medium,High',
        ]);

        $category = Category::create($validated);
        return back()->with('status', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'department' => 'sometimes|string',
            'sla_days' => 'sometimes|integer|min:1',
            'severity' => 'sometimes|string|in:Low,Medium,High',
        ]);

        $category->update($validated);
        return back()->with('status', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('status', 'Category deleted successfully.');
    }
}
