<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;

class AboutPageController extends Controller
{
    public function show()
    {
        $about = AboutPage::query()->latest('id')->first();

        if (!$about) {
            return response()->json(['data' => null]);
        }

        return response()->json([
            'data' => [
                'id' => $about->id,
                'title' => $about->title,
                'content' => $about->content,
                'updated_at' => $about->updated_at,
            ],
        ]);
    }
}


