<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Story;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $banner = Banner::query()
            ->where('is_active', true)
            ->orderBy('position')
            ->first();

        $categories = Category::query()
            ->where('is_active', true)
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        $recentStories = Story::query()
            ->where('status', Story::STATUS_APPROVED)
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        return view('home', [
            'banner' => $banner,
            'categories' => $categories,
            'recentStories' => $recentStories,
        ]);
    }
}


