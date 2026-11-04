<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('products')->orderBy('order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|max:3072',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = '/storage/' . $request->file('image')->store('categories', 'public');
        }

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'order' => (int) ($validated['order'] ?? 0),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Category added.');
    }

    public function update(Request $request, int $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|max:3072',
        ]);

        if ($request->hasFile('image')) {
            $category->image = '/storage/' . $request->file('image')->store('categories', 'public');
        }

        $category->name = $validated['name'];
        $category->description = $validated['description'] ?? null;
        $category->order = (int) ($validated['order'] ?? 0);
        $category->is_active = $request->boolean('is_active');
        $category->save();

        return back()->with('success', 'Category updated.');
    }

    public function destroy(int $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return back()->with('success', 'Category deleted.');
    }
}
