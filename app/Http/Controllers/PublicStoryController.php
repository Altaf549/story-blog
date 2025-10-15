<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\Category;
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
            'category' => null,
        ]);
    }

    public function byCategory(string $slug): View
    {
        $category = Category::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $stories = Story::query()
            ->where('status', Story::STATUS_APPROVED)
            ->where('category_id', $category->id)
            ->orderByDesc('id')
            ->paginate(12);

        return view('stories.index', [
            'stories' => $stories,
            'category' => $category,
        ]);
    }

    public function show(Story $story): View
    {
        abort_if($story->status !== Story::STATUS_APPROVED, 404);

        return view('stories.show', [
            'story' => $story,
        ]);
    }
}


