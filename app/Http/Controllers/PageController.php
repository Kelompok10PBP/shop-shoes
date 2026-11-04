<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('pages.about');
    }

    public function sizeGuide(): View
    {
        return view('pages.size-guide');
    }

    public function shipping(): View
    {
        return view('pages.shipping');
    }

    public function faq(): View
    {
        return view('pages.faq');
    }
}
