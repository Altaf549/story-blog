<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactPage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactPageController extends Controller
{
	public function index(): View
	{
		$record = ContactPage::first();
		return view('admin.contact.index', ['record' => $record]);
	}

	public function store(Request $request)
	{
        $validated = $request->validate([
            'title' => ['required','string','max:255'],
            'phone_no' => ['nullable','string','max:255'],
            'email' => ['nullable','email','max:255'],
            'address' => ['nullable','string'],
        ]);

		$record = ContactPage::first() ?: new ContactPage();
        $record->fill($validated);
		$record->save();

		return back()->with('success', 'Contact Us saved');
	}
}


