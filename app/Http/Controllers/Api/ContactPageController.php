<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactPage;

class ContactPageController extends Controller
{
    public function show()
    {
        $contact = ContactPage::query()->latest('id')->first();

        if (!$contact) {
            return response()->json(['data' => null]);
        }

        return response()->json([
            'data' => [
                'id' => $contact->id,
                'title' => $contact->title,
                'phone_no' => $contact->phone_no,
                'email' => $contact->email,
                'address' => $contact->address,
                'updated_at' => $contact->updated_at,
            ],
        ]);
    }
}


