<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/verify/{member_id}', [PublicController::class, 'verifyMember'])->name('verify.member');

// Authenticated user routes
Route::middleware('auth:api')->group(function () {
    // Use profileApi for API, profile for web
    Route::get('/me', [UserController::class, 'profileApi']);
    Route::post('/user/profile-photo', [UserController::class, 'uploadProfilePhoto']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // KYC
    Route::post('/kyc/upload', [KycController::class, 'upload']);
    Route::get('/kyc/my-documents', [KycController::class, 'myDocuments']);

    // Wallet
    Route::get('/wallet/balance', [WalletController::class, 'balance']);
    Route::get('/wallet/transactions', [WalletController::class, 'transactions']);
});

// Admin routes
Route::middleware(['auth:api', 'admin'])->prefix('admin')->group(function () {
    // Member management
    Route::get('/members/pending', [AdminController::class, 'pendingMembers']);
    Route::post('/members/{user}/approve', [AdminController::class, 'approveMember']);
    Route::post('/members/{user}/reject', [AdminController::class, 'rejectMember']);

    // KYC management
    Route::get('/kyc/pending', [AdminController::class, 'pendingKyc']);
    Route::post('/kyc/{kyc}/approve', [AdminController::class, 'approveKyc']);
    Route::post('/kyc/{kyc}/reject', [AdminController::class, 'rejectKyc']);

    // Wallet management
    Route::post('/wallets/{user}/add', [AdminController::class, 'addCredit']);
});