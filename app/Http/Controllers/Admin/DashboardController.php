<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = now()->startOfDay();

        // 1. Core KPIs
        $ordersToday = Order::where('created_at', '>=', $today)->count();
        $revenueToday = (float) Order::where('created_at', '>=', $today)->where('status', '!=', 'cancelled')->sum('total_amount');
        $revenue7Days = (float) Order::where('created_at', '>=', now()->subDays(7))->where('status', '!=', 'cancelled')->sum('total_amount');
        $revenue30Days = (float) Order::where('created_at', '>=', now()->subDays(30))->where('status', '!=', 'cancelled')->sum('total_amount');
        $totalCustomers = User::where('role', 'customer')->count();

        // 2. Low Stock Alerts (Variants with stock <= 5)
        $lowStockVariants = ProductVariant::where('stock', '<=', 5)
            ->with('product')
            ->orderBy('stock', 'asc')
            ->take(8)
            ->get();

        // 3. Top-Selling Products
        $topSellers = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product.category', 'product.images')
            ->take(5)
            ->get();

        // 4. Recent Orders
        $recentOrders = Order::with('user')->latest()->take(6)->get();

        // 5. 7-day revenue chart data
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayLabel = now()->subDays($i)->format('D, M j');
            $rev = Order::whereDate('created_at', $date)->where('status', '!=', 'cancelled')->sum('total_amount');
            $chartLabels[] = $dayLabel;
            $chartData[] = (float) $rev;
        }

        return view('admin.dashboard', compact(
            'ordersToday',
            'revenueToday',
            'revenue7Days',
            'revenue30Days',
            'totalCustomers',
            'lowStockVariants',
            'topSellers',
            'recentOrders',
            'chartLabels',
            'chartData'
        ));
    }
}
