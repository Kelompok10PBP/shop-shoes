<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(): View
    {
        $orders = Auth::user()->orders()
            ->with(['items.product.images', 'payment'])
            ->latest()
            ->paginate(10);

        return view('account.orders.index', compact('orders'));
    }

    public function show(string $orderNumber): View
    {
        $order = Auth::user()->orders()
            ->where('order_number', $orderNumber)
            ->with(['items.product.images', 'payment'])
            ->firstOrFail();

        return view('account.orders.show', compact('order'));
    }

    public function cancel(string $orderNumber)
    {
        $order = Auth::user()->orders()
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be cancelled. Please contact concierge for processed orders.');
        }

        // Restore stock
        foreach ($order->items as $item) {
            if ($item->variant) {
                $item->variant->increment('stock', $item->quantity);
            }
        }

        $order->status = 'cancelled';
        $order->save();

        return back()->with('success', 'Order ' . $order->order_number . ' has been cancelled and inventory restocked.');
    }

    public function reorder(string $orderNumber)
    {
        $order = Auth::user()->orders()
            ->where('order_number', $orderNumber)
            ->with('items')
            ->firstOrFail();

        foreach ($order->items as $item) {
            $this->cartService->addItem($item->product_id, $item->product_variant_id, $item->quantity);
        }

        return redirect()->route('cart.index')->with('success', 'Items from order ' . $order->order_number . ' added to your bag!');
    }
}
