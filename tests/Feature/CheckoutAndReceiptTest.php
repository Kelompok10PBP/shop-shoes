<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Order;
use Tests\TestCase;

class CheckoutAndReceiptTest extends TestCase
{
    public function test_can_process_checkout_and_generate_receipt(): void
    {
        $product = Product::where('status', 'active')->first();
        $this->assertNotNull($product, 'Product should exist in database');

        $variant = $product->variants()->first();

        // 1. Add product to bag
        $response = $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'product_variant_id' => $variant?->id,
            'quantity' => 1,
        ]);
        $response->assertStatus(200);

        // 2. Submit Checkout
        $checkoutData = [
            'recipient_name' => 'Budi Santoso',
            'guest_email' => 'budi.santoso@example.com',
            'phone' => '081234567890',
            'address_line1' => 'Jl. Sudirman No. 45',
            'city' => 'Jakarta Selatan',
            'state' => 'DKI Jakarta',
            'postal_code' => '12920',
            'country' => 'Indonesia',
            'shipping_method' => 'standard',
            'payment_method' => 'card',
            'card_number' => '4242 4242 4242 4242',
        ];

        $checkoutResponse = $this->post('/checkout', $checkoutData);

        // 3. Assert redirected to success receipt page
        $checkoutResponse->assertRedirect();
        $redirectUrl = $checkoutResponse->headers->get('Location');
        $this->assertStringContainsString('/checkout/success/STR-', $redirectUrl);

        // Extract order number
        preg_match('/STR-\d+/', $redirectUrl, $matches);
        $this->assertNotEmpty($matches);
        $orderNumber = $matches[0];

        // 4. Follow redirect and check receipt page content
        $receiptResponse = $this->get('/checkout/success/' . $orderNumber);
        $receiptResponse->assertStatus(200);
        $receiptResponse->assertSee('STRUK RESMI');
        $receiptResponse->assertSee('LUNAS');
        $receiptResponse->assertSee($orderNumber);
        $receiptResponse->assertSee('Budi Santoso');
        $receiptResponse->assertSee($product->name);
        $receiptResponse->assertSee('Rp ');
        $receiptResponse->assertDontSee('$ ');
        $receiptResponse->assertDontSee('USD');

        // 5. Check thermal receipt view
        $thermalResponse = $this->get('/checkout/receipt/' . $orderNumber);
        $thermalResponse->assertStatus(200);
        $thermalResponse->assertSee('STRIDE FOOTWEAR');
        $thermalResponse->assertSee($orderNumber);
        $thermalResponse->assertSee('Rp ');
        $thermalResponse->assertDontSee('$ ');
        $thermalResponse->assertDontSee('USD');
    }
}
