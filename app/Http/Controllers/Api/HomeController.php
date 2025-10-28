<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Story;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::query()
            ->where('is_active', true)
            ->orderBy('position')
            ->get(['id', 'title', 'image_id', 'link_url', 'position'])
            ->map(function (Banner $banner) {
                return [
                    'id' => $banner->id,
                    'title' => $banner->title,
                    'image_id' => $banner->image_id,
                    'link_url' => $banner->link_url,
                    'position' => $banner->position,
                ];
            });

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->limit(6)
            ->get(['id', 'name', 'slug', 'description', 'image_id'])
            ->map(function (Category $category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'image_id' => $category->image_id
                ];
            });

        $stories = Story::query()
            ->with(['user:id,name', 'category:id,name,slug'])
            ->where('status', Story::STATUS_APPROVED)
            ->latest()
            ->limit(6)
            ->get()
            ->map(function (Story $story) {
                return [
                    'id' => $story->id,
                    'title' => $story->title,
                    'content' => $story->content,
                    'image_id' => $story->image_id,
                    'youtube_url' => $story->youtube_url,
                    'user' => $story->user ? [
                        'id' => $story->user->id,
                        'name' => $story->user->name,
                    ] : null,
                    'category' => $story->category ? [
                        'id' => $story->category->id,
                        'name' => $story->category->name,
                        'slug' => $story->category->slug,
                    ] : null,
                    'created_at' => $story->created_at,
                ];
            });

        return response()->json([
            'banners' => $banners,
            'categories' => $categories,
            'stories' => $stories,
        ]);
    }
}


