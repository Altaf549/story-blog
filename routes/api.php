<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController as ApiCategoryController;
use App\Http\Controllers\Api\StoryController as ApiStoryController;
use App\Http\Controllers\Api\HomeController as ApiHomeController;
use App\Http\Controllers\Api\PrivacyPolicyController as ApiPrivacyPolicyController;
use App\Http\Controllers\Api\TermsConditionController as ApiTermsConditionController;
use App\Http\Controllers\Api\AboutPageController as ApiAboutPageController;
use App\Http\Controllers\Api\ContactPageController as ApiContactPageController;

// Public API endpoints
Route::get('/home', [ApiHomeController::class, 'index']);
Route::get('/categories', [ApiCategoryController::class, 'index']);
Route::get('/stories', [ApiStoryController::class, 'index']);
Route::get('/categories/{slug}/stories', [ApiStoryController::class, 'byCategory']);
Route::get('/privacy-policy', [ApiPrivacyPolicyController::class, 'show']);
Route::get('/terms-conditions', [ApiTermsConditionController::class, 'show']);
Route::get('/about', [ApiAboutPageController::class, 'show']);
Route::get('/contact', [ApiContactPageController::class, 'show']);


