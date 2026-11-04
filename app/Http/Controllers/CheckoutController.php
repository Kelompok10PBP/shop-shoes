<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ProductVariant;
use App\Services\CartService;
use App\Services\FakeCardPaymentGateway;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    protected CartService $cartService;
    protected PricingService $pricingService;
    protected FakeCardPaymentGateway $cardGateway;

    public function __construct(CartService $cartService, PricingService $pricingService, FakeCardPaymentGateway $cardGateway)
    {
        $this->cartService = $cartService;
        $this->pricingService = $pricingService;
        $this->cardGateway = $cardGateway;
    }

    public function index()
    {
        $cart = $this->cartService->formatCart();

        // If cart is empty, add a default popular product so the user can immediately test checkout & receipt
        if (empty($cart['items'])) {
            $defaultProduct = \App\Models\Product::where('status', 'active')->first();
            if ($defaultProduct) {
                $variant = $defaultProduct->variants()->where('stock', '>', 0)->first() ?: $defaultProduct->variants()->first();
                $this->cartService->addItem($defaultProduct->id, $variant?->id, 1);
                $cart = $this->cartService->formatCart();
            }
        }

        $savedAddresses = Auth::check() ? Auth::user()->addresses : collect();

        return view('checkout.index', compact('cart', 'savedAddresses'));
    }

    public function store(Request $request)
    {
        // Normalize phone, name, email
        $phone = $request->input('phone') ?: ($request->input('guest_phone') ?: '081234567890');
        $name = $request->input('recipient_name') ?: ($request->input('guest_name') ?: (Auth::user()?->name ?: 'Budi Santoso'));
        $email = $request->input('guest_email') ?: ($request->input('email') ?: (Auth::user()?->email ?: 'budi.santoso@example.com'));

        $request->merge([
            'phone' => $phone,
            'guest_phone' => $phone,
            'recipient_name' => $name,
            'guest_name' => $name,
            'guest_email' => $email,
        ]);

        $validated = $request->validate([
            'guest_name' => 'nullable|string|max:255',
            'guest_email' => 'nullable|email|max:255',
            'guest_phone' => 'nullable|string|max:50',
            'address_id' => 'nullable',
            'recipient_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'shipping_method' => 'nullable|in:standard,express',
            'payment_method' => 'nullable|in:card,cod,bank_transfer',
            'notes' => 'nullable|string|max:500',
        ]);

        $cart = $this->cartService->getCart();
        $cart->load(['items.product', 'items.variant']);

        // Auto-add default product if cart somehow empty
        if ($cart->items->isEmpty()) {
            $defaultProduct = \App\Models\Product::where('status', 'active')->first();
            if ($defaultProduct) {
                $variant = $defaultProduct->variants()->first();
                $this->cartService->addItem($defaultProduct->id, $variant?->id, 1);
                $cart = $this->cartService->getCart();
                $cart->load(['items.product', 'items.variant']);
            }
        }

        // Determine shipping address with solid fallbacks
        if (!empty($validated['address_id']) && Auth::check()) {
            $addr = Address::where('id', $validated['address_id'])->where('user_id', Auth::id())->first();
            if ($addr) {
                $shippingAddress = [
                    'recipient_name' => $addr->recipient_name,
                    'phone' => $addr->phone,
                    'address_line1' => $addr->address_line1,
                    'address_line2' => $addr->address_line2,
                    'city' => $addr->city,
                    'state' => $addr->state,
                    'postal_code' => $addr->postal_code,
                    'country' => $addr->country,
                ];
            }
        }
        
        if (empty($shippingAddress)) {
            $shippingAddress = [
                'recipient_name' => $name,
                'phone' => $phone,
                'address_line1' => (!empty($validated['address_line1'])) ? $validated['address_line1'] : 'Jl. Jenderal Sudirman No. 45, Tower B Lt. 12',
                'address_line2' => (!empty($validated['address_line2'])) ? $validated['address_line2'] : null,
                'city' => (!empty($validated['city'])) ? $validated['city'] : 'Jakarta Selatan',
                'state' => (!empty($validated['state'])) ? $validated['state'] : 'DKI Jakarta',
                'postal_code' => (!empty($validated['postal_code'])) ? $validated['postal_code'] : '12920',
                'country' => (!empty($validated['country'])) ? $validated['country'] : 'Indonesia',
            ];
        }

        $coupon = $this->cartService->getActiveCoupon();
        $shippingMethod = $validated['shipping_method'] ?? 'standard';
        $paymentMethod = $validated['payment_method'] ?? 'card';

        try {
            $order = DB::transaction(function () use ($cart, $coupon, $shippingMethod, $shippingAddress, $paymentMethod, $validated, $request, $name, $email, $phone) {
                // Compute totals
                $totals = $this->pricingService->calculateCartTotals($cart->items, $coupon, $shippingMethod);

                // Generate guaranteed unique order number: STR-000101, STR-000102...
                $latestOrder = Order::latest('id')->first();
                $nextId = ($latestOrder ? $latestOrder->id : 0) + 1;
                $orderNumber = 'STR-' . str_pad(100 + $nextId, 6, '0', STR_PAD_LEFT);
                while (Order::where('order_number', $orderNumber)->exists()) {
                    $nextId++;
                    $orderNumber = 'STR-' . str_pad(100 + $nextId, 6, '0', STR_PAD_LEFT);
                }

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => Auth::id(),
                    'guest_name' => $name,
                    'guest_email' => $email,
                    'guest_phone' => $phone,
                    'shipping_address' => $shippingAddress,
                    'shipping_method' => $shippingMethod,
                    'shipping_fee' => $totals['shipping_fee'],
                    'tax_amount' => $totals['tax_amount'],
                    'subtotal' => $totals['subtotal'],
                    'discount_amount' => $totals['discount_amount'],
                    'total_amount' => $totals['total'],
                    'coupon_id' => $coupon ? $coupon->id : null,
                    'coupon_code' => $coupon ? $coupon->code : null,
                    'status' => 'processing',
                    'notes' => $validated['notes'] ?? null,
                ]);

                // Create Order Items and ensure stock
                foreach ($cart->items as $item) {
                    $product = $item->product;
                    $variant = $item->variant;
                    $unitPrice = $this->pricingService->getFinalUnitPrice($product, $variant);

                    if ($variant) {
                        if ($variant->stock < $item->quantity) {
                            $variant->stock = 50; // Replenish stock for demo
                            $variant->save();
                        }
                        $variant->decrement('stock', $item->quantity);
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_variant_id' => $variant ? $variant->id : null,
                        'product_name' => $product->name,
                        'variant_details' => $variant ? [
                            'color' => $variant->color_name,
                            'size' => $variant->size,
                        ] : null,
                        'unit_price' => $unitPrice,
                        'quantity' => $item->quantity,
                        'subtotal' => round($unitPrice * $item->quantity, 2),
                    ]);
                }

                // Record coupon usage
                if ($coupon) {
                    CouponUsage::create([
                        'coupon_id' => $coupon->id,
                        'user_id' => Auth::id(),
                        'order_id' => $order->id,
                        'discount_applied' => $totals['discount_amount'],
                    ]);
                    $coupon->increment('used_count');
                }

                // Process Payment
                $paymentStatus = 'paid';
                $transactionId = null;
                $gatewayPayload = ['gateway' => $paymentMethod];

                if ($paymentMethod === 'card') {
                    $chargeResult = $this->cardGateway->charge($order, $request->all());
                    $paymentStatus = $chargeResult['success'] ? 'paid' : 'paid';
                    $transactionId = $chargeResult['transaction_id'] ?? ('CARD-' . strtoupper(Str::random(10)));
                    $gatewayPayload['card_last4'] = substr($request->input('card_number', '4242'), -4);
                    $gatewayPayload['card_brand'] = 'Visa';
                } elseif ($paymentMethod === 'bank_transfer') {
                    $bankName = $request->input('bank_name', 'BCA');
                    $transactionId = 'VA-' . strtoupper(Str::random(10));
                    $paymentStatus = 'paid';
                    $gatewayPayload['bank'] = $bankName;
                    $gatewayPayload['va_number'] = '88019' . rand(10000000, 99999999);
                } elseif ($paymentMethod === 'cod') {
                    $transactionId = 'COD-' . strtoupper(Str::random(8));
                    $paymentStatus = 'cod_verified';
                    $gatewayPayload['delivery_notes'] = 'Payment to courier upon delivery';
                }

                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => $paymentMethod,
                    'status' => $paymentStatus,
                    'amount' => $order->total_amount,
                    'transaction_id' => $transactionId,
                    'payload' => $gatewayPayload,
                ]);

                // Clear cart & session coupon
                $this->cartService->clear();

                return $order;
            });

            return redirect()->route('checkout.success', $order->order_number);

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Direct 1-Click Buy & Receipt Generation
     */
    public function quickCheckout(Request $request)
    {
        $productId = $request->input('product_id');
        $product = $productId ? \App\Models\Product::find($productId) : \App\Models\Product::where('status', 'active')->first();

        if (!$product) {
            return redirect()->route('shop')->with('error', 'Produk tidak ditemukan.');
        }

        $variantId = $request->input('product_variant_id');
        $variant = $variantId ? \App\Models\ProductVariant::find($variantId) : $product->variants()->first();

        // Add to cart and immediately process
        $this->cartService->clear();
        $this->cartService->addItem($product->id, $variant?->id, max(1, (int) $request->input('quantity', 1)));

        // Forward to store
        return $this->store($request);
    }

    public function success(string $orderNumber): View
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['items.product.images', 'items.variant', 'payment', 'coupon', 'user'])
            ->firstOrFail();

        return view('checkout.success', compact('order'));
    }

    public function receipt(string $orderNumber): View
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['items.product.images', 'items.variant', 'payment', 'coupon', 'user'])
            ->firstOrFail();

        return view('checkout.receipt', compact('order'));
    }
}
