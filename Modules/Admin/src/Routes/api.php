<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminController;

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/admin/users', [AdminController::class, 'getAllUsers']);
    Route::get('/admin/users/{id}', [AdminController::class, 'getUserById']);
});
