<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // 1. Hero Banner
        $heroBanner = Banner::where('type', 'hero')->where('is_active', true)->first();

        // 2. Maximum active discount in catalog
        $maxDiscount = Product::where('is_discount_active', true)
            ->where('discount_type', 'percent')
            ->max('discount_value') ?: 30;

        // 3. Categories with dynamic model counts
        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->orderBy('order')
            ->get();

        // 4. Promo Countdown Banner
        $promotion = Promotion::where('is_active', true)->latest()->first();

        // 5. Trending Products (initial 5 items)
        $trendingProducts = Product::where('status', 'active')
            ->where('is_trending', true)
            ->with(['category', 'variants', 'images', 'reviews'])
            ->take(5)
            ->get();

        // 6. Trust Stats
        $overallRating = round((float) (Review::avg('rating') ?: 4.9), 1);
        $totalReviews = Review::count();

        return view('home', compact(
            'heroBanner',
            'maxDiscount',
            'categories',
            'promotion',
            'trendingProducts',
            'overallRating',
            'totalReviews'
        ));
    }

    /**
     * AJAX endpoint for trending filter tabs
     */
    public function trending(Request $request)
    {
        $categorySlug = $request->query('category');

        $query = Product::where('status', 'active')
            ->where('is_trending', true)
            ->with(['category', 'variants', 'images', 'reviews']);

        if ($categorySlug && $categorySlug !== 'all') {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        $products = $query->take(5)->get();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('partials.trending-grid', compact('products'))->render(),
                'count' => $products->count(),
            ]);
        }

        return redirect()->route('home');
    }
}
