<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TermsCondition;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TermsConditionController extends Controller
{
	public function index(): View
	{
		$record = TermsCondition::first();
		return view('admin.terms.index', ['record' => $record]);
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'title' => ['required','string','max:255'],
			'content' => ['nullable','string'],
		]);

		$record = TermsCondition::first() ?: new TermsCondition();
		$record->fill($validated);
		$record->save();

		return back()->with('success', 'Terms & Conditions saved');
	}
}



