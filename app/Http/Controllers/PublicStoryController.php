<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\View\View;

class PublicStoryController extends Controller
{
    public function index(): View
    {
        $stories = Story::query()
            ->where('status', Story::STATUS_APPROVED)
            ->orderByDesc('id')
            ->paginate(12);

        return view('stories.index', [
            'stories' => $stories,
        ]);
    }
}


