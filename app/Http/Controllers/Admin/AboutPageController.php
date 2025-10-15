<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AboutPageController extends Controller
{
	public function index(): View
	{
		$record = AboutPage::first();
		return view('admin.about.index', ['record' => $record]);
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'title' => ['required','string','max:255'],
			'content' => ['nullable','string'],
		]);

		$record = AboutPage::first() ?: new AboutPage();
		$record->fill($validated);
		$record->save();

		return back()->with('success', 'About Us saved');
	}
}


