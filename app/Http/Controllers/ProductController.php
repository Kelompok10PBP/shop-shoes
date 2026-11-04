<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(string $slug): View
    {
        $product = Product::where('slug', $slug)
            ->where('status', 'active')
            ->with(['category', 'brand', 'variants', 'images', 'reviews.user'])
            ->firstOrFail();

        // Related products in same category
        $relatedProducts = Product::where('status', 'active')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['category', 'variants', 'images', 'reviews'])
            ->take(4)
            ->get();

        // Track recently viewed in session
        $recentIds = Session::get('recently_viewed', []);
        $recentIds = array_diff($recentIds, [$product->id]);
        array_unshift($recentIds, $product->id);
        $recentIds = array_slice($recentIds, 0, 8);
        Session::put('recently_viewed', $recentIds);

        // Fetch other recently viewed
        $recentlyViewed = Product::whereIn('id', array_slice($recentIds, 1, 4))
            ->where('status', 'active')
            ->with(['category', 'variants', 'images', 'reviews'])
            ->get();

        return view('products.show', compact('product', 'relatedProducts', 'recentlyViewed'));
    }
}
