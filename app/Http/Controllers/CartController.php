<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function mini(): JsonResponse
    {
        return response()->json($this->cartService->formatCart());
    }

    public function add(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1|max:50',
        ]);

        $this->cartService->addItem(
            $validated['product_id'],
            $validated['product_variant_id'] ?? null,
            $validated['quantity'] ?? 1
        );

        return response()->json([
            'success' => true,
            'message' => 'Added to your bag!',
            'cart' => $this->cartService->formatCart(),
        ]);
    }

    public function buyNow(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1|max:50',
        ]);

        $this->cartService->addItem(
            $validated['product_id'],
            $validated['product_variant_id'] ?? null,
            $validated['quantity'] ?? 1
        );

        return redirect()->route('checkout.index');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0|max:50',
        ]);

        $this->cartService->updateItemQuantity($id, $validated['quantity']);

        return response()->json([
            'success' => true,
            'cart' => $this->cartService->formatCart(),
        ]);
    }

    public function remove(int $id): JsonResponse
    {
        $this->cartService->removeItem($id);

        return response()->json([
            'success' => true,
            'cart' => $this->cartService->formatCart(),
        ]);
    }

    public function index(): View
    {
        $cart = $this->cartService->formatCart();
        return view('cart.index', compact('cart'));
    }

    public function applyCoupon(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50',
        ]);

        $code = strtoupper(trim($validated['code']));
        $coupon = Coupon::where('code', $code)->where('is_active', true)->first();

        if (!$coupon) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Invalid discount code.'], 422);
            }
            return back()->with('error', 'Invalid discount code.');
        }

        $cart = $this->cartService->formatCart();
        if (!$coupon->isValidForAmount($cart['subtotal'])) {
            $msg = "Minimum spend of $" . number_format($coupon->min_spend, 2) . " required for this coupon.";
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        Session::put('applied_coupon_code', $coupon->code);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Coupon applied successfully!',
                'cart' => $this->cartService->formatCart(),
            ]);
        }

        return back()->with('success', 'Coupon applied successfully!');
    }

    public function removeCoupon(Request $request)
    {
        Session::forget('applied_coupon_code');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cart' => $this->cartService->formatCart(),
            ]);
        }

        return back()->with('info', 'Coupon removed.');
    }
}
