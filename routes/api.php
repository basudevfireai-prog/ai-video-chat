<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminTokenVerificationMiddleware;
// use App\Http\Middleware\TokenVerificationMiddleware;
use App\Http\Middleware\UserTokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

// User Routes
Route::post('/user-login', [UserController::class, 'userLogin']);
Route::post('/user-registration', [UserController::class, 'userRegistration']);
Route::post('/user-send-otp', [UserController::class, 'sendOtp']);
Route::post('/user-verify-otp', [UserController::class, 'verifyOtp']);
Route::post('/user-reset-password', [UserController::class, 'resetPassword'])->middleware([ UserTokenVerificationMiddleware::class]);
// Route::post('/user-reset-password', [UserController::class, 'resetPassword']);

Route::get('/user-logout', [UserController::class,'userLogout'])->middleware([UserTokenVerificationMiddleware::class]);
Route::get('/user/{id}/show', [UserController::class,'userShow'])->middleware([UserTokenVerificationMiddleware::class]);

// Admin Routes
// Route::post('/admin-dashboard', [AdminController::class, 'adminDashboard'])->middleware([TokenVerificationMiddleware::class]);
Route::post('/admin-login', [AdminController::class, 'adminLogin']);
Route::post('/admin-send-otp', [AdminController::class, 'sendOtp']);
Route::post('/admin-verify-otp', [AdminController::class, 'verifyOtp']);
Route::post('/admin-reset-password', [AdminController::class, 'resetPassword'])->middleware([AdminTokenVerificationMiddleware::class]);
Route::get('/admin-logout', [AdminController::class, 'adminLogout'])->middleware([AdminTokenVerificationMiddleware::class]);

Route::get('/all-users', [AdminController::class, 'allUsers'])->middleware([AdminTokenVerificationMiddleware::class]);

// Role Routes
Route::post('/create-role', [RoleController::class, 'createRole'])->middleware([AdminTokenVerificationMiddleware::class]);

// Chat Routes
Route::post('/session-start', [ChatController::class, 'sessionStart'])->middleware([UserTokenVerificationMiddleware::class]);

Route::post('/ai-chat-start', [ChatController::class, 'aiChatStart'])->middleware([UserTokenVerificationMiddleware::class]);

Route::post('/session-played', [ChatController::class, 'sessionPlayed'])->middleware([UserTokenVerificationMiddleware::class]);

Route::get('/home', [ChatController::class, 'home'])->middleware([UserTokenVerificationMiddleware::class]);


