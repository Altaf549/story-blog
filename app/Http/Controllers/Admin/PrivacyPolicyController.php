<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrivacyPolicy;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrivacyPolicyController extends Controller
{
	public function index(): View
	{
		$record = PrivacyPolicy::first();
		return view('admin.privacy.index', ['record' => $record]);
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'title' => ['required','string','max:255'],
			'content' => ['nullable','string'],
		]);

		$record = PrivacyPolicy::first() ?: new PrivacyPolicy();
		$record->fill($validated);
		$record->save();

		return back()->with('success', 'Privacy Policy saved');
	}
}



