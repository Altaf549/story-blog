<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

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
        // Add more protected admin routes here
    });
});

// Redirect /admin to /admin/dashboard if authenticated, otherwise to login
Route::redirect('/admin', '/admin/dashboard')->middleware('auth');
Route::redirect('/admin', '/admin/login')->middleware('guest');
