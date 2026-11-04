<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = Review::with(['product.images', 'user']);

        if ($request->filled('status')) {
            $isApproved = ($request->query('status') === 'approved');
            $query->where('is_approved', $isApproved);
        }

        $reviews = $query->latest()->paginate(20)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggleApprove(int $id)
    {
        $review = Review::findOrFail($id);
        $review->is_approved = !$review->is_approved;
        $review->save();

        return back()->with('success', 'Review status updated.');
    }

    public function destroy(int $id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return back()->with('success', 'Review deleted.');
    }
}
