<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function edit(): View
    {
        $banner = Banner::where('type', 'hero')->firstOrCreate(
            ['type' => 'hero'],
            [
                'pill_label' => 'New Collection 2025',
                'title' => 'Step Into Your',
                'title_highlight' => 'Best Pace',
                'subtitle' => 'Precision-engineered running and everyday performance footwear built with responsive bio-foam cushioning for 10K+ runs and daily stride.',
                'cta_text' => 'Shop Now',
                'cta_url' => '/shop',
                'secondary_cta_text' => 'Explore Collections',
                'secondary_cta_url' => '/collections',
                'tech_tag_text' => 'BioFoam Propulsion v2',
                'discount_badge_percent' => 30,
                'is_active' => true,
            ]
        );

        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request)
    {
        $banner = Banner::where('type', 'hero')->firstOrFail();

        $validated = $request->validate([
            'pill_label' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'title_highlight' => 'required|string|max:255',
            'subtitle' => 'required|string',
            'cta_text' => 'required|string|max:100',
            'cta_url' => 'required|string|max:255',
            'secondary_cta_text' => 'required|string|max:100',
            'secondary_cta_url' => 'required|string|max:255',
            'tech_tag_text' => 'required|string|max:100',
            'discount_badge_percent' => 'required|integer|min:0|max:100',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $banner->image = '/storage/' . $request->file('image')->store('banners', 'public');
        }

        $banner->pill_label = $validated['pill_label'];
        $banner->title = $validated['title'];
        $banner->title_highlight = $validated['title_highlight'];
        $banner->subtitle = $validated['subtitle'];
        $banner->cta_text = $validated['cta_text'];
        $banner->cta_url = $validated['cta_url'];
        $banner->secondary_cta_text = $validated['secondary_cta_text'];
        $banner->secondary_cta_url = $validated['secondary_cta_url'];
        $banner->tech_tag_text = $validated['tech_tag_text'];
        $banner->discount_badge_percent = $validated['discount_badge_percent'];
        $banner->is_active = $request->boolean('is_active', true);
        $banner->save();

        return back()->with('success', 'Hero banner settings updated successfully.');
    }
}
