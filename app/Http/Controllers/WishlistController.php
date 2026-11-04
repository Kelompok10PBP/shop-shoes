<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class WishlistController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(): View
    {
        if (Auth::check()) {
            $wishlists = Wishlist::where('user_id', Auth::id())
                ->with('product.category', 'product.images', 'product.variants')
                ->latest()
                ->get();
        } else {
            $sessionId = Session::getId();
            $wishlists = Wishlist::where('session_id', $sessionId)
                ->with('product.category', 'product.images', 'product.variants')
                ->latest()
                ->get();
        }

        return view('account.wishlist', compact('wishlists'));
    }

    public function toggle(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = $validated['product_id'];

        if (Auth::check()) {
            $existing = Wishlist::where('user_id', Auth::id())->where('product_id', $productId)->first();
            if ($existing) {
                $existing->delete();
                return response()->json(['success' => true, 'is_in_wishlist' => false]);
            }

            Wishlist::create(['user_id' => Auth::id(), 'product_id' => $productId]);
            return response()->json(['success' => true, 'is_in_wishlist' => true]);
        }

        $sessionId = Session::getId();
        $existing = Wishlist::where('session_id', $sessionId)->where('product_id', $productId)->first();
        if ($existing) {
            $existing->delete();
            return response()->json(['success' => true, 'is_in_wishlist' => false]);
        }

        Wishlist::create(['session_id' => $sessionId, 'product_id' => $productId]);
        return response()->json(['success' => true, 'is_in_wishlist' => true]);
    }

    public function moveToCart(int $id)
    {
        $wishlist = Wishlist::findOrFail($id);
        $product = $wishlist->product;

        if ($product) {
            $firstVariant = $product->variants()->where('stock', '>', 0)->first();
            $this->cartService->addItem($product->id, $firstVariant ? $firstVariant->id : null, 1);
            $wishlist->delete();
        }

        return back()->with('success', 'Moved to bag!');
    }
}
