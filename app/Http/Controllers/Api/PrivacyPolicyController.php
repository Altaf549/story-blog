<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrivacyPolicy;

class PrivacyPolicyController extends Controller
{
    public function show()
    {
        $policy = PrivacyPolicy::query()->latest('id')->first();

        if (!$policy) {
            return response()->json(['data' => null]);
        }

        return response()->json([
            'data' => [
                'id' => $policy->id,
                'title' => $policy->title,
                'content' => $policy->content,
                'updated_at' => $policy->updated_at,
            ],
        ]);
    }
}


