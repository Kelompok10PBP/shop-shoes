<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, int $productId)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|max:2000',
        ]);

        $product = Product::findOrFail($productId);
        $user = Auth::user();

        // Check if verified buyer who purchased this product
        $verifiedOrder = Order::where('user_id', $user->id)
            ->whereIn('status', ['delivered', 'shipped', 'processing'])
            ->whereHas('items', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->first();

        // Prevent duplicate reviews
        $existing = Review::where('user_id', $user->id)->where('product_id', $productId)->first();
        if ($existing) {
            return back()->with('error', 'You have already submitted a review for this shoe model.');
        }

        Review::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'order_id' => $verifiedOrder ? $verifiedOrder->id : null,
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'comment' => $validated['comment'],
            'is_verified_purchase' => (bool) $verifiedOrder,
            'is_approved' => true,
        ]);

        return back()->with('success', 'Thank you! Your athlete review has been published.');
    }
}
