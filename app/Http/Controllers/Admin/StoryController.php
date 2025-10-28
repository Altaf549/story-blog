<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StoryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $stories = Story::with(['user', 'category'])->latest()->paginate(20);
        return view('admin.stories.index', compact('stories', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'image_id' => ['required', 'string', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
        ]);

        $story = new Story($validated);
        $story->user_id = auth()->id();
        $story->status = Story::STATUS_PENDING;
        $story->save();

        return back()->with('success', 'Story created');
    }

    public function update(Request $request, Story $story): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'image_id' => ['required', 'string', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
        ]);

        $story->update($validated);
        return back()->with('success', 'Story updated');
    }

    public function destroy(Story $story): RedirectResponse
    {
        $story->delete();
        return back()->with('success', 'Story deleted');
    }

    public function changeStatus(Request $request, Story $story): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:' . implode(',', [Story::STATUS_PENDING, Story::STATUS_APPROVED, Story::STATUS_REJECTED])],
        ]);
        $story->status = $validated['status'];
        $story->save();

        return back()->with('success', 'Status updated');
    }
}


