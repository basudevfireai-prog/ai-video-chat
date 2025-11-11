<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use App\Models\User;
use Illuminate\Support\Facades\Route;


// User Routes
Route::post('user-login', [UserController::class, 'userLogin']);
Route::post('/user-registration', [UserController::class, 'userRegistration']);
Route::post('/user-send-otp', [UserController::class, 'sendOtp']);
Route::post('/user-verify-otp', [UserController::class, 'verifyOtp']);
Route::post('/user-reset-password', [UserController::class, 'resetPassword'])->middleware([ TokenVerificationMiddleware::class]);

Route::get('/user-logout', [UserController::class,'UserLogout'])->middleware([TokenVerificationMiddleware::class]);
