<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'items']);

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('search')) {
            $s = $request->query('search');
            $query->where(function ($q) use ($s) {
                $q->where('order_number', 'like', "%{$s}%")
                  ->orWhere('guest_name', 'like', "%{$s}%")
                  ->orWhere('guest_email', 'like', "%{$s}%")
                  ->orWhereHas('user', function ($uq) use ($s) {
                      $uq->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(int $id): View
    {
        $order = Order::with(['user', 'items.product.images', 'items.variant', 'payment', 'coupon'])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $order = Order::findOrFail($id);
        $oldStatus = $order->status;

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $newStatus = $validated['status'];
        $order->status = $newStatus;
        $order->save();

        // Restore stock if transitioning to cancelled
        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->variant) {
                    $item->variant->increment('stock', $item->quantity);
                }
            }
        }

        return back()->with('success', 'Order status updated to ' . ucfirst($newStatus) . '.');
    }

    public function invoice(int $id): View
    {
        $order = Order::with(['user', 'items.product', 'payment'])->findOrFail($id);
        return view('admin.orders.invoice', compact('order'));
    }
}
