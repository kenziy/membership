<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SsoController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/verify/{member_id}', [PublicController::class, 'verifyMember'])->name('public.verify');

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    
    // Profile
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::post('/profile', [UserController::class, 'profileUpdate'])->name('user.profile.update');
    Route::post('/profile/photo', [UserController::class, 'uploadProfilePhoto'])->name('user.profile.photo');
    
    // KYC
    Route::get('/kyc', [UserController::class, 'kyc'])->name('user.kyc');
    Route::post('/kyc/upload', [KycController::class, 'upload'])->name('user.kyc.upload');
    
    // Wallet
    Route::get('/wallet', [UserController::class, 'wallet'])->name('user.wallet');
    
    // ID Card
    Route::get('/id-card', [UserController::class, 'idCard'])->name('user.id-card');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // KYC Management
    Route::get('/kyc/pending', [AdminController::class, 'pendingKyc'])->name('admin.kyc.pending');
    Route::post('/kyc/{kyc}/approve', [AdminController::class, 'approveKyc'])->name('admin.kyc.approve');
    Route::post('/kyc/{kyc}/reject', [AdminController::class, 'rejectKyc'])->name('admin.kyc.reject');
    
    // Wallet Management
    Route::get('/wallets', [AdminController::class, 'wallets'])->name('admin.wallets');
    Route::post('/wallets/{user}/add', [AdminController::class, 'addCredit'])->name('admin.wallets.add');

    // Member Management
    Route::get('/members', [AdminController::class, 'membersIndex'])->name('admin.members.index');
    Route::get('/members/pending', [AdminController::class, 'pendingMembers'])->name('admin.members.pending');
    Route::get('/members/pending', [AdminController::class, 'pendingMembers'])->name('admin.members.pending');
    Route::post('/members/{user}/approve', [AdminController::class, 'approveMember'])->name('admin.members.approve');
    Route::post('/members/{user}/reject', [AdminController::class, 'rejectMember'])->name('admin.members.reject');
    Route::post('/members/{user}/approve', [AdminController::class, 'approveMember'])->name('admin.members.approve');
    Route::post('/members/{user}/reject', [AdminController::class, 'rejectMember'])->name('admin.members.reject');
    Route::post('/members/{user}/toggle-vip', [AdminController::class, 'toggleVip'])->name('admin.members.toggle-vip');
});

Route::prefix('sso')->group(function () {
    // Initiate SSO flows
    Route::get('/login', [SsoController::class, 'login'])->name('sso.login');
    Route::get('/register', [SsoController::class, 'register'])->name('sso.register');
    
    // Process SSO actions
    Route::post('/process-login', [SsoController::class, 'processLogin'])->name('sso.process-login');
    Route::post('/process-register', [SsoController::class, 'processRegister'])->name('sso.process-register');
    
    // Token verification (for client apps)
    Route::post('/verify', [SsoController::class, 'verifyToken'])->name('sso.verify');
    
    // Configuration endpoint
    Route::get('/config', [SsoController::class, 'configuration'])->name('sso.config');
});