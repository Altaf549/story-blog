<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TermsCondition;

class TermsConditionController extends Controller
{
    public function show()
    {
        $terms = TermsCondition::query()->latest('id')->first();

        if (!$terms) {
            return response()->json(['data' => null]);
        }

        return response()->json([
            'data' => [
                'id' => $terms->id,
                'title' => $terms->title,
                'content' => $terms->content,
                'updated_at' => $terms->updated_at,
            ],
        ]);
    }
}


