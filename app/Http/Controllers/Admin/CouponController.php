<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(): View
    {
        $coupons = Coupon::withCount('usages')->latest()->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0.01',
            'min_spend' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        Coupon::create([
            'code' => strtoupper($validated['code']),
            'type' => $validated['type'],
            'value' => $validated['value'],
            'min_spend' => $validated['min_spend'] ?? 0,
            'max_discount' => $validated['max_discount'] ?? null,
            'usage_limit' => $validated['usage_limit'] ?? null,
            'expires_at' => $validated['expires_at'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Coupon created.');
    }

    public function update(Request $request, int $id)
    {
        $coupon = Coupon::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0.01',
            'min_spend' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        $coupon->update([
            'code' => strtoupper($validated['code']),
            'type' => $validated['type'],
            'value' => $validated['value'],
            'min_spend' => $validated['min_spend'] ?? 0,
            'max_discount' => $validated['max_discount'] ?? null,
            'usage_limit' => $validated['usage_limit'] ?? null,
            'expires_at' => $validated['expires_at'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Coupon updated.');
    }

    public function destroy(int $id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return back()->with('success', 'Coupon deleted.');
    }
}
