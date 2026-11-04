<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function dashboard(): View
    {
        $user = Auth::user();
        $recentOrders = $user->orders()->with('items.product.images')->latest()->take(3)->get();
        $defaultAddress = $user->addresses()->where('is_default', true)->first() ?: $user->addresses()->first();

        return view('account.dashboard', compact('user', 'recentOrders', 'defaultAddress'));
    }
}
