<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $existing = NewsletterSubscriber::where('email', $validated['email'])->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'This email address is already subscribed to the Stride Club!',
                'coupon_code' => 'WELCOME15',
            ]);
        }

        // Generate or retrieve welcome coupon
        $coupon = Coupon::where('code', 'WELCOME15')->first();

        NewsletterSubscriber::create([
            'email' => $validated['email'],
            'coupon_id' => $coupon ? $coupon->id : null,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Welcome aboard! Enjoy your 15% discount.',
            'coupon_code' => $coupon ? $coupon->code : 'WELCOME15',
        ]);
    }
}
