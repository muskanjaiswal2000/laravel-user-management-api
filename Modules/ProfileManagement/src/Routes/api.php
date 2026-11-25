<?php

use Illuminate\Support\Facades\Route;
use Modules\ProfileManagement\Http\Controllers\ProfileController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'getProfile']);
    Route::put('/profile', [ProfileController::class, 'updateProfile']);
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar']);
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar']);
});
