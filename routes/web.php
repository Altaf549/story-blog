<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\StoryController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\PrivacyPolicyController;
use App\Http\Controllers\Admin\TermsConditionController;
use App\Http\Controllers\Admin\AboutPageController;
use App\Http\Controllers\Admin\ContactPageController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login']);
    
    // Protected routes
    Route::middleware(['auth'])->group(function () {
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Category Management Routes
        Route::resource('categories', 'App\Http\Controllers\Admin\CategoryController')
            ->names([
                'index' => 'admin.categories.index',
                'create' => 'admin.categories.create',
                'store' => 'admin.categories.store',
                'show' => 'admin.categories.show',
                'edit' => 'admin.categories.edit',
                'update' => 'admin.categories.update',
                'destroy' => 'admin.categories.destroy',
            ]);

        // Story Management Routes
        Route::resource('stories', StoryController::class)->only(['index','store','update','destroy'])
            ->names([
                'index' => 'admin.stories.index',
                'store' => 'admin.stories.store',
                'update' => 'admin.stories.update',
                'destroy' => 'admin.stories.destroy',
            ]);
        Route::patch('stories/{story}/status', [StoryController::class, 'changeStatus'])->name('admin.stories.status');

        // Settings Pages split tables
        Route::get('privacy-policy', [PrivacyPolicyController::class, 'index'])->name('admin.privacy.index');
        Route::post('privacy-policy', [PrivacyPolicyController::class, 'store'])->name('admin.privacy.store');

        Route::get('terms-conditions', [TermsConditionController::class, 'index'])->name('admin.terms.index');
        Route::post('terms-conditions', [TermsConditionController::class, 'store'])->name('admin.terms.store');

        Route::get('about-us', [AboutPageController::class, 'index'])->name('admin.about.index');
        Route::post('about-us', [AboutPageController::class, 'store'])->name('admin.about.store');

        Route::get('contact-us', [ContactPageController::class, 'index'])->name('admin.contact.index');
        Route::post('contact-us', [ContactPageController::class, 'store'])->name('admin.contact.store');

        // Banner Management
        Route::resource('banners', BannerController::class)->only(['index','store','update','destroy'])
            ->names([
                'index' => 'admin.banners.index',
                'store' => 'admin.banners.store',
                'update' => 'admin.banners.update',
                'destroy' => 'admin.banners.destroy',
            ]);
    });
});

// Redirect /admin to /admin/dashboard if authenticated, otherwise to login
Route::redirect('/admin', '/admin/dashboard')->middleware('auth');
Route::redirect('/admin', '/admin/login')->middleware('guest');

// Duplicate of admin group resource; removing to prevent route conflicts
// (index, store, edit, update, destroy are already defined under the auth-protected admin prefix)
