<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function suggest(Request $request): JsonResponse
    {
        $query = trim($request->query('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['products' => []]);
        }

        $products = Product::where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('subtitle', 'like', "%{$query}%")
                  ->orWhereHas('category', function ($cq) use ($query) {
                      $cq->where('name', 'like', "%{$query}%");
                  });
            })
            ->with(['category', 'images'])
            ->take(6)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'category' => $p->category ? $p->category->name : '',
                    'price' => $p->final_price,
                    'image' => $p->display_image,
                ];
            });

        return response()->json(['products' => $products]);
    }

    public function index(Request $request): View
    {
        $query = trim($request->query('q', ''));

        $productsQuery = Product::where('status', 'active')
            ->with(['category', 'variants', 'images', 'reviews']);

        if ($query) {
            $productsQuery->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('subtitle', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhereHas('category', function ($cq) use ($query) {
                      $cq->where('name', 'like', "%{$query}%");
                  });
            });
        }

        $products = $productsQuery->paginate(12)->withQueryString();

        return view('search', compact('products', 'query'));
    }
}
