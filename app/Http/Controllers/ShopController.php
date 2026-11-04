<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('status', 'active')
            ->with(['category', 'brand', 'variants', 'images', 'reviews']);

        // Filter by category
        if ($request->filled('category')) {
            $catSlug = $request->query('category');
            $query->whereHas('category', function ($q) use ($catSlug) {
                $q->where('slug', $catSlug);
            });
        }

        // Filter by brand
        if ($request->filled('brand')) {
            $brandSlug = $request->query('brand');
            $query->whereHas('brand', function ($q) use ($brandSlug) {
                $q->where('slug', $brandSlug);
            });
        }

        // Filter by size
        if ($request->filled('size')) {
            $size = $request->query('size');
            $query->whereHas('variants', function ($q) use ($size) {
                $q->where('size', $size);
            });
        }

        // Filter by color
        if ($request->filled('color')) {
            $color = $request->query('color');
            $query->whereHas('variants', function ($q) use ($color) {
                $q->where('color_name', 'like', "%{$color}%");
            });
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', (float) $request->query('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', (float) $request->query('max_price'));
        }

        // Filter on sale
        if ($request->boolean('sale')) {
            $query->where(function ($q) {
                $q->where('is_discount_active', true)
                  ->orWhere('badge', 'SALE')
                  ->orWhereNotNull('compare_at_price');
            });
        }

        // Sorting
        $sort = $request->query('sort', 'featured');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('base_price', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating');
                break;
            default:
                $query->orderByDesc('is_featured')->orderByDesc('is_trending')->latest();
                break;
        }

        $products = $query->paginate(15)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('partials.shop-products', compact('products'))->render(),
                'pagination' => (string) $products->links(),
                'total' => $products->total(),
            ]);
        }

        $categories = Category::where('is_active', true)->withCount('products')->get();
        $brands = Brand::withCount('products')->get();
        $availableSizes = ['7.0', '7.5', '8.0', '8.5', '9.0', '9.5', '10.0', '10.5', '11.0', '11.5', '12.0'];

        return view('shop.index', compact('products', 'categories', 'brands', 'availableSizes'));
    }

    public function bestSellers(): View
    {
        $products = Product::where('status', 'active')
            ->where(function ($q) {
                $q->where('badge', 'BEST SELLER')
                  ->orWhere('is_featured', true);
            })
            ->with(['category', 'variants', 'images', 'reviews'])
            ->paginate(15);

        $title = 'Best Sellers';
        $subtitle = 'Our most coveted road, trail, and minimalist daily silhouettes';

        return view('shop.special', compact('products', 'title', 'subtitle'));
    }

    public function newArrivals(): View
    {
        $products = Product::where('status', 'active')
            ->where(function ($q) {
                $q->where('is_new', true)
                  ->orWhere('created_at', '>=', now()->subDays(60));
            })
            ->with(['category', 'variants', 'images', 'reviews'])
            ->latest()
            ->paginate(15);

        $title = 'New Releases';
        $subtitle = 'Fresh seasonal drops, updated colorways, and bio-foam advancements';

        return view('shop.special', compact('products', 'title', 'subtitle'));
    }
}
