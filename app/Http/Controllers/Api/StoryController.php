<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);

        $stories = Story::query()
            ->with(['user:id,name', 'category:id,name,slug'])
            ->where('status', Story::STATUS_APPROVED)
            ->latest()
            ->paginate($perPage);

        $stories->getCollection()->transform(function (Story $story) {
            return [
                'id' => $story->id,
                'title' => $story->title,
                'content' => $story->content,
                'banner_image' => $story->banner_image,
                'banner_image_url' => $story->banner_image ? Storage::url($story->banner_image) : null,
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

        return response()->json($stories);
    }

    public function byCategory(Request $request, string $slug)
    {
        $perPage = (int) $request->query('per_page', 15);

        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $stories = Story::query()
            ->with(['user:id,name', 'category:id,name,slug'])
            ->where('status', Story::STATUS_APPROVED)
            ->where('category_id', $category->id)
            ->latest()
            ->paginate($perPage);

        $stories->getCollection()->transform(function (Story $story) use ($category) {
            return [
                'id' => $story->id,
                'title' => $story->title,
                'content' => $story->content,
                'banner_image' => $story->banner_image,
                'banner_image_url' => $story->banner_image ? Storage::url($story->banner_image) : null,
                'user' => $story->user ? [
                    'id' => $story->user->id,
                    'name' => $story->user->name,
                ] : null,
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ],
                'created_at' => $story->created_at,
            ];
        });

        return response()->json([
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ],
            'stories' => $stories,
        ]);
    }
}


