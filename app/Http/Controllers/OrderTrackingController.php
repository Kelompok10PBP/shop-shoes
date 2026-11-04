<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderTrackingController extends Controller
{
    public function index(): View
    {
        return view('order-tracking.index');
    }

    public function lookup(Request $request): View
    {
        $validated = $request->validate([
            'order_number' => 'required|string|max:50',
            'email' => 'required|email|max:255',
        ]);

        $orderNumber = strtoupper(trim($validated['order_number']));
        $email = strtolower(trim($validated['email']));

        $order = Order::where('order_number', $orderNumber)
            ->where(function ($q) use ($email) {
                $q->where('guest_email', $email)
                  ->orWhereHas('user', function ($uq) use ($email) {
                      $uq->where('email', $email);
                  });
            })
            ->with(['items.product.images', 'payment'])
            ->first();

        return view('order-tracking.result', compact('order', 'orderNumber', 'email'));
    }
}
