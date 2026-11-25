<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthWebController;
use App\Http\Controllers\UserWebController;
use App\Http\Controllers\AdminWebController;
use App\Http\Middleware\SessionAuth;
use App\Http\Middleware\AdminSessionAuth;

Route::get('/', function () {
    if (session('api_token')) {
        if (session('user')['role'] === 'admin') {
            return redirect('/admin/dashboard');
        }
        return redirect('/profile');
    }
    return redirect('/login');
});

// Authentication Routes
Route::get('/login', [AuthWebController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthWebController::class, 'login']);
Route::get('/register', [AuthWebController::class, 'showRegister']);
Route::post('/register', [AuthWebController::class, 'register']);
Route::post('/logout', [AuthWebController::class, 'logout'])->name('logout');

// Password Recovery Routes
Route::get('/forgot-password', [AuthWebController::class, 'showForgotPassword']);
Route::post('/forgot-password', [AuthWebController::class, 'forgotPassword']);
Route::get('/verify-otp', [AuthWebController::class, 'showVerifyOTP']);
Route::post('/verify-otp', [AuthWebController::class, 'verifyOTP']);
Route::get('/reset-password', [AuthWebController::class, 'showResetPassword']);
Route::post('/reset-password', [AuthWebController::class, 'resetPassword']);

// User Routes (Protected by session)
Route::middleware(['web'])->group(function () {
    Route::get('/profile', [UserWebController::class, 'showProfile'])->middleware(SessionAuth::class);
    Route::put('/profile', [UserWebController::class, 'updateProfile'])->middleware(SessionAuth::class);
    Route::delete('/profile/avatar', [UserWebController::class, 'deleteAvatar'])->middleware(SessionAuth::class);
});

// Admin Routes (Protected by session)
Route::middleware(['web'])->group(function () {
    Route::get('/admin/dashboard', [AdminWebController::class, 'dashboard'])->middleware(AdminSessionAuth::class);
    Route::get('/admin/users', [AdminWebController::class, 'users'])->middleware(AdminSessionAuth::class);
    Route::get('/admin/users/{id}', [AdminWebController::class, 'showUser'])->middleware(AdminSessionAuth::class);
});
