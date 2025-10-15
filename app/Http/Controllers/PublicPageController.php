<?php

namespace App\Http\Controllers;

use App\Models\PrivacyPolicy;
use App\Models\TermsCondition;
use App\Models\AboutPage;
use App\Models\ContactPage;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    public function privacy(): View
    {
        $page = PrivacyPolicy::query()->latest('id')->first();
        return view('pages.privacy', ['page' => $page]);
    }

    public function terms(): View
    {
        $page = TermsCondition::query()->latest('id')->first();
        return view('pages.terms', ['page' => $page]);
    }

    public function about(): View
    {
        $page = AboutPage::query()->latest('id')->first();
        return view('pages.about', ['page' => $page]);
    }

    public function contact(): View
    {
        $page = ContactPage::query()->latest('id')->first();
        return view('pages.contact', ['page' => $page]);
    }
}


