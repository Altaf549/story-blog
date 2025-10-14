<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\StoryController;

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
    });
});

// Redirect /admin to /admin/dashboard if authenticated, otherwise to login
Route::redirect('/admin', '/admin/dashboard')->middleware('auth');
Route::redirect('/admin', '/admin/login')->middleware('guest');

// Duplicate of admin group resource; removing to prevent route conflicts
// (index, store, edit, update, destroy are already defined under the auth-protected admin prefix)
