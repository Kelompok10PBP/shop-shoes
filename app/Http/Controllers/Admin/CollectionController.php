<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CollectionController extends Controller
{
    public function index(): View
    {
        $collections = Collection::withCount('products')->get();
        return view('admin.collections.index', compact('collections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|max:3072',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = '/storage/' . $request->file('image')->store('collections', 'public');
        }

        Collection::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Collection created.');
    }

    public function update(Request $request, int $id)
    {
        $collection = Collection::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|max:3072',
        ]);

        if ($request->hasFile('image')) {
            $collection->image = '/storage/' . $request->file('image')->store('collections', 'public');
        }

        $collection->name = $validated['name'];
        $collection->description = $validated['description'] ?? null;
        $collection->is_active = $request->boolean('is_active');
        $collection->save();

        return back()->with('success', 'Collection updated.');
    }

    public function destroy(int $id)
    {
        $collection = Collection::findOrFail($id);
        $collection->delete();

        return back()->with('success', 'Collection deleted.');
    }
}
