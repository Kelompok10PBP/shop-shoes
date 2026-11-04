<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected PricingService $pricingService;

    public function __construct(PricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }

    /**
     * Get or create current active cart
     */
    public function getCart(): Cart
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            // If there's a guest session cart, merge it
            $sessionId = Session::getId();
            $guestCart = Cart::where('session_id', $sessionId)->whereNull('user_id')->first();
            if ($guestCart && $guestCart->id !== $cart->id) {
                $this->mergeCarts($guestCart, $cart);
            }
            return $cart;
        }

        $sessionId = Session::getId();
        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }

    /**
     * Merge guest cart into user cart
     */
    public function mergeCarts(Cart $guestCart, Cart $userCart): void
    {
        foreach ($guestCart->items as $guestItem) {
            $existing = $userCart->items()
                ->where('product_id', $guestItem->product_id)
                ->where('product_variant_id', $guestItem->product_variant_id)
                ->first();

            if ($existing) {
                $existing->quantity += $guestItem->quantity;
                $existing->save();
            } else {
                $guestItem->cart_id = $userCart->id;
                $guestItem->save();
            }
        }

        $guestCart->delete();
    }

    /**
     * Add product to cart
     */
    public function addItem(int $productId, ?int $variantId = null, int $quantity = 1): CartItem
    {
        $cart = $this->getCart();
        $product = Product::findOrFail($productId);
        
        $item = $cart->items()
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($item) {
            $item->quantity += max(1, $quantity);
            $item->save();
        } else {
            $item = $cart->items()->create([
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'quantity' => max(1, $quantity),
            ]);
        }

        return $item;
    }

    /**
     * Update cart item quantity
     */
    public function updateItemQuantity(int $itemId, int $quantity): ?CartItem
    {
        $cart = $this->getCart();
        $item = $cart->items()->where('id', $itemId)->first();

        if (!$item) {
            return null;
        }

        if ($quantity <= 0) {
            $item->delete();
            return null;
        }

        $item->quantity = $quantity;
        $item->save();
        return $item;
    }

    /**
     * Remove item from cart
     */
    public function removeItem(int $itemId): bool
    {
        $cart = $this->getCart();
        return (bool) $cart->items()->where('id', $itemId)->delete();
    }

    /**
     * Clear all items
     */
    public function clear(): void
    {
        $cart = $this->getCart();
        $cart->items()->delete();
        Session::forget('applied_coupon_code');
    }

    /**
     * Get active coupon if stored in session
     */
    public function getActiveCoupon(): ?Coupon
    {
        $code = Session::get('applied_coupon_code');
        if (!$code) return null;

        $coupon = Coupon::where('code', $code)->where('is_active', true)->first();
        if ($coupon && (!$coupon->expires_at || $coupon->expires_at->isFuture())) {
            return $coupon;
        }

        Session::forget('applied_coupon_code');
        return null;
    }

    /**
     * Format cart payload for JSON / Alpine.js
     */
    public function formatCart(): array
    {
        $cart = $this->getCart();
        $cart->load(['items.product.images', 'items.variant']);

        $coupon = $this->getActiveCoupon();
        $totals = $this->pricingService->calculateCartTotals($cart->items, $coupon);

        $items = $cart->items->map(function (CartItem $item) {
            $variant = $item->variant;
            $variantLabel = $variant 
                ? trim(($variant->color_name ? $variant->color_name . ' / ' : '') . 'US ' . $variant->size) 
                : 'Standard Edition';

            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'variant_id' => $item->product_variant_id,
                'name' => $item->product ? $item->product->name : 'Footwear Item',
                'slug' => $item->product ? $item->product->slug : '#',
                'image' => $item->product ? $item->product->display_image : '/images/placeholder-shoe.png',
                'variant_label' => $variantLabel,
                'unit_price' => $item->unit_price,
                'quantity' => $item->quantity,
                'subtotal' => $item->subtotal,
            ];
        });

        return array_merge($totals, [
            'id' => $cart->id,
            'items' => $items,
            'coupon' => $coupon ? [
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => (float) $coupon->value,
            ] : null,
        ]);
    }
}
