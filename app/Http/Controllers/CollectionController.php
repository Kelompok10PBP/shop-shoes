<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CollectionController extends Controller
{
    public function index(): View
    {
        $collections = Collection::where('is_active', true)
            ->withCount('products')
            ->get();

        return view('collections.index', compact('collections'));
    }

    public function show(string $slug): View
    {
        $collection = Collection::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $products = $collection->products()
            ->where('status', 'active')
            ->with(['category', 'variants', 'images', 'reviews'])
            ->paginate(12);

        return view('collections.show', compact('collection', 'products'));
    }
}
