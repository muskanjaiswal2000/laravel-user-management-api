<?php

use Illuminate\Support\Facades\Route;
use Modules\Authentication\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/verify-otp', [AuthController::class, 'verifyOTP']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
