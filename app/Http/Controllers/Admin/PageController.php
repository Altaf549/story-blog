<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
	/**
	 * Display and edit static pages (Privacy, Terms, About, Contact).
	 */
	public function index(): View
	{
		$pages = Page::whereIn('key', [
			'privacy_policy',
			'terms_and_conditions',
			'about_us',
			'contact_us',
		])->get()->keyBy('key');

		return view('admin.pages.index', [
			'pages' => $pages,
		]);
	}

	/**
	 * Store or update a page by key.
	 */
	public function store(Request $request)
	{
		$validated = $request->validate([
			'key' => ['required', 'in:privacy_policy,terms_and_conditions,about_us,contact_us'],
			'title' => ['required', 'string', 'max:255'],
			'content' => ['nullable', 'string'],
		]);

		$page = Page::firstOrNew(['key' => $validated['key']]);
		$page->title = $validated['title'];
		$page->content = $validated['content'] ?? null;
		$page->save();

		return back()->with('success', 'Page saved successfully');
	}
}


