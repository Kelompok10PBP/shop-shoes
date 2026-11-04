<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterController extends Controller
{
    public function index(): View
    {
        $subscribers = NewsletterSubscriber::with('coupon')->latest()->paginate(25);
        return view('admin.newsletter.index', compact('subscribers'));
    }

    public function exportCsv(): StreamedResponse
    {
        $subscribers = NewsletterSubscriber::all();

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=stride_newsletter_subscribers.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($subscribers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Email', 'Active', 'Subscribed At']);

            foreach ($subscribers as $s) {
                fputcsv($handle, [$s->id, $s->email, $s->is_active ? 'Yes' : 'No', $s->created_at]);
            }
            fclose($handle);
        }, 200, $headers);
    }
}
