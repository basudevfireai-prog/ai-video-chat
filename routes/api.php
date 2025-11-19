<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

// User Routes
Route::post('/user-login', [UserController::class, 'userLogin']);
Route::post('/user-registration', [UserController::class, 'userRegistration']);
Route::post('/user-send-otp', [UserController::class, 'sendOtp']);
Route::post('/user-verify-otp', [UserController::class, 'verifyOtp']);
Route::post('/user-reset-password', [UserController::class, 'resetPassword'])->middleware([ TokenVerificationMiddleware::class]);

Route::get('/user-logout', [UserController::class,'userLogout'])->middleware([TokenVerificationMiddleware::class]);

// Admin Routes
Route::post('/admin-dashboard', [AdminController::class, 'adminDashboard'])->middleware([TokenVerificationMiddleware::class]);
Route::post('/admin-login', [AdminController::class, 'adminLogin']);
Route::post('/admin-send-otp', [AdminController::class, 'sendOtp']);
Route::post('/admin-verify-otp', [AdminController::class, 'verifyOtp']);
Route::post('/admin-reset-password', [AdminController::class, 'resetPassword'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/admin-logout', [AdminController::class, 'adminLogout'])->middleware([TokenVerificationMiddleware::class]);


// Role Routes
Route::post('/create-role', [RoleController::class, 'createRole'])->middleware([TokenVerificationMiddleware::class]);


// Chat Routes
Route::post('/session-start', [ChatController::class, 'sessionStart'])->middleware([TokenVerificationMiddleware::class]);

Route::post('/ai-chat-start', [ChatController::class, 'aiChatStart'])->middleware([TokenVerificationMiddleware::class]);

Route::post('/session-played', [ChatController::class, 'sessionPlayed'])->middleware([TokenVerificationMiddleware::class]);

Route::get('/home', [ChatController::class, 'home'])->middleware([TokenVerificationMiddleware::class]);
