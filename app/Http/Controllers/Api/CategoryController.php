<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Story;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->withCount(['stories as approved_stories_count' => function ($query) {
                $query->where('status', Story::STATUS_APPROVED);
            }])
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'description', 'image']);

        $data = $categories->map(function (Category $category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'image' => $category->image,
                'image_url' => $category->image ? Storage::url($category->image) : null,
                'approved_stories_count' => $category->approved_stories_count,
            ];
        });

        return response()->json([
            'data' => $data,
        ]);
    }
}


