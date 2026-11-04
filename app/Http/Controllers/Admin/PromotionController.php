<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PromotionController extends Controller
{
    public function edit(): View
    {
        $promotion = Promotion::latest()->firstOrCreate(
            ['title' => 'Spring Performance Sale — Up to 40% Off Select Running Models'],
            [
                'subtitle' => 'Spring Sale Is Live!',
                'description' => 'Limited inventory on our carbon-plated racers and daily trainers. Engineered for long-distance durability. Ends midnight Sunday.',
                'discount_text' => 'Up to 40% Off',
                'cta_text' => 'Shop the Sale →',
                'cta_url' => '/shop?sale=1',
                'starts_at' => now(),
                'ends_at' => now()->addDays(5),
                'is_active' => true,
            ]
        );

        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request)
    {
        $promotion = Promotion::firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'required|string',
            'discount_text' => 'nullable|string|max:100',
            'cta_text' => 'required|string|max:100',
            'cta_url' => 'required|string|max:255',
            'ends_at' => 'required|date',
            'is_active' => 'nullable|boolean',
            'image_1' => 'nullable|image|max:5120',
            'image_2' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image_1')) {
            $promotion->image_1 = '/storage/' . $request->file('image_1')->store('banners', 'public');
        }
        if ($request->hasFile('image_2')) {
            $promotion->image_2 = '/storage/' . $request->file('image_2')->store('banners', 'public');
        }

        $promotion->title = $validated['title'];
        $promotion->subtitle = $validated['subtitle'] ?? null;
        $promotion->description = $validated['description'];
        $promotion->discount_text = $validated['discount_text'] ?? null;
        $promotion->cta_text = $validated['cta_text'];
        $promotion->cta_url = $validated['cta_url'];
        $promotion->ends_at = $validated['ends_at'];
        $promotion->is_active = $request->boolean('is_active', true);
        $promotion->save();

        return back()->with('success', 'Promo countdown banner updated.');
    }
}
