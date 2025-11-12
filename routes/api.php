<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;


// User Routes
Route::post('/user-login', [UserController::class, 'userLogin']);
Route::post('/user-registration', [UserController::class, 'userRegistration']);
Route::post('/user-send-otp', [UserController::class, 'sendOtp']);
Route::post('/user-verify-otp', [UserController::class, 'verifyOtp']);
Route::post('/user-reset-password', [UserController::class, 'resetPassword'])->middleware([ TokenVerificationMiddleware::class]);

Route::get('/user-logout', [UserController::class,'userLogout'])->middleware([TokenVerificationMiddleware::class]);

// Admin Routes
Route::post('/admin-login', [AdminController::class, 'adminLogin']);
Route::post('/admin-dashboard', [AdminController::class, 'adminDashboard'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/admin-logout', [AdminController::class, 'adminLogout'])->middleware([TokenVerificationMiddleware::class]);


// Ai Chat Token Routes


// Role Routes
Route::post('/create-role', [RoleController::class, 'createRole']);

