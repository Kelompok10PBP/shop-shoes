<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Coupon;

class PricingService
{
    /**
     * Calculate final unit price for a product and optional variant
     */
    public function getFinalUnitPrice(Product $product, ?ProductVariant $variant = null): float
    {
        $basePrice = $variant && $variant->price_override ? (float) $variant->price_override : (float) $product->base_price;

        if ($product->is_discount_active) {
            $now = now();
            $starts = $product->discount_start_at ? $product->discount_start_at <= $now : true;
            $ends = $product->discount_end_at ? $product->discount_end_at >= $now : true;

            if ($starts && $ends) {
                if ($product->discount_type === 'percent') {
                    $discount = ($basePrice * (float) $product->discount_value) / 100;
                    $basePrice = max(0, $basePrice - $discount);
                } elseif ($product->discount_type === 'fixed') {
                    $basePrice = max(0, $basePrice - (float) $product->discount_value);
                }
            }
        }

        return round($basePrice, 2);
    }

    /**
     * Calculate line item subtotal
     */
    public function calculateItemSubtotal(Product $product, ?ProductVariant $variant, int $quantity): float
    {
        $unitPrice = $this->getFinalUnitPrice($product, $variant);
        return round($unitPrice * max(1, $quantity), 2);
    }

    /**
     * Calculate cart totals with coupon and shipping
     */
    public function calculateCartTotals(iterable $items, ?Coupon $coupon = null, string $shippingMethod = 'standard'): array
    {
        $subtotal = 0.0;
        $totalItems = 0;

        foreach ($items as $item) {
            $product = $item instanceof \App\Models\CartItem ? $item->product : $item['product'];
            $variant = $item instanceof \App\Models\CartItem ? $item->variant : ($item['variant'] ?? null);
            $qty = $item instanceof \App\Models\CartItem ? $item->quantity : (int) $item['quantity'];

            $subtotal += $this->calculateItemSubtotal($product, $variant, $qty);
            $totalItems += $qty;
        }

        $subtotal = round($subtotal, 2);

        // Free shipping threshold from settings or default Rp 500.000
        $freeShippingThreshold = (float) \App\Models\Setting::get('free_shipping_threshold', 500000.0);
        $standardShippingFee = $subtotal >= $freeShippingThreshold ? 0.0 : (float) \App\Models\Setting::get('standard_shipping_fee', 0.0);
        $expressShippingFee = (float) \App\Models\Setting::get('express_shipping_fee', 45000.0);

        $shippingFee = $shippingMethod === 'express' ? $expressShippingFee : $standardShippingFee;

        // Coupon calculation
        $discountAmount = 0.0;
        if ($coupon && $coupon->isValidForAmount($subtotal)) {
            $discountAmount = $coupon->calculateDiscount($subtotal);
        }

        $taxRate = (float) \App\Models\Setting::get('tax_rate', 0.0); // e.g. 0.08 for 8%
        $taxableAmount = max(0, $subtotal - $discountAmount);
        $taxAmount = round($taxableAmount * $taxRate, 2);

        $total = max(0, round($subtotal - $discountAmount + $shippingFee + $taxAmount, 2));

        $freeShippingRemaining = max(0, round($freeShippingThreshold - $subtotal, 2));
        $freeShippingUnlocked = $subtotal >= $freeShippingThreshold;

        return [
            'subtotal' => $subtotal,
            'total_items' => $totalItems,
            'discount_amount' => $discountAmount,
            'shipping_fee' => $shippingFee,
            'tax_amount' => $taxAmount,
            'total' => $total,
            'free_shipping_threshold' => $freeShippingThreshold,
            'free_shipping_remaining' => $freeShippingRemaining,
            'free_shipping_unlocked' => $freeShippingUnlocked,
            'free_shipping_progress' => min(100, round(($subtotal / $freeShippingThreshold) * 100)),
        ];
    }
}
