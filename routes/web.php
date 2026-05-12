<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController as AuthLoginController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\SequenceController;
use App\Http\Controllers\Users\Master;
use App\Http\Controllers\Users\MasterController;
use Illuminate\Support\Facades\Route;

// user info
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthLoginController::class, 'Register'])->name('register-data');
    Route::post('/register', [AuthLoginController::class, 'SubmitRegister'])->name('submit_register');

    Route::get('/', [AuthLoginController::class, 'Login'])->name('login-data');
    Route::post('/', [AuthLoginController::class, 'SubmitLogin'])->name('login');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'Dashboard'])->name('dashboard');

    // profile page
    Route::get('/user-profile', [DashboardController::class, 'UserProfile'])->name('user-profile');
    Route::post('/submit-profile-update', [DashboardController::class, 'submitProfileUpdate'])->name('submit-profile-update');
    Route::get('/submit-profile', [DashboardController::class, 'profile'])->name('profile-remove-image');

    // user google mail send
    Route::get('/connect-gmail', [GoogleController::class, 'redirect']);
    Route::get('/google/callback', [GoogleController::class, 'callback']);

    // master page
    Route::get('/master', [MasterController::class, 'masterViewPage'])->name('master-view-page');
    Route::get('/Link', [MasterController::class, 'masterLinkDocument'])->name('master-link-document');
    Route::post('/BusinessLinks', [MasterController::class, 'submitBusinessLinks'])->name('submit-business-links');
    // In web.php
    Route::get('/admin/business-links', [MasterController::class, 'getBusinessLinks'])->name('user-business-links');
    Route::post('/sequences/store', [MasterController::class, 'sequencesStore'])->name('user-sequences-store');
});

// admin info
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'AdminDashboard'])->name('admin-dashboard');
    Route::get('/profile', [AdminDashboardController::class, 'adminProfile'])->name('admin-profile');
    Route::post('/admin-submit', [AdminDashboardController::class, 'adminUpdateDataUpdate'])->name('admin-submit-profile-update');

    Route::post('/admin-users-data', [UserController::class, 'data'])->name('admin-users-data');
    Route::post('/admin-users-store', [UserController::class, 'store'])->name('admin-users-store');
    Route::get('/admin-users-total', [UserController::class, 'totalUsers'])->name('admin-users-total');
    Route::put('/admin-users-update', [UserController::class, 'update'])->name('admin-users-update');
    Route::get('/admin-users-edit/{id}', [UserController::class, 'editUserData'])->name('admin-users-edit');
    Route::post('/admin-update-users', [UserController::class, 'updateUserData'])->name('admin-update-user-data');
    Route::post('/admin-users-toggle-status', [UserController::class, 'toggleStatus'])->name('admin-users-toggle-status');
    Route::delete('/users-destroy', [UserController::class, 'destroy'])->name('admin-users-destroy');
    Route::get('/users-export', [UserController::class, 'export'])->name('admin-users-export');
    Route::get('/users-print', [UserController::class, 'printCard'])->name('admin-users-print');
    Route::get('/users', [UserController::class, 'index'])->name('admin-users-index');

    Route::get('/sequences', [SequenceController::class, 'sequencesIndex'])->name('admin-sequences-index');
    Route::get('/sequences/create', [SequenceController::class, 'sequencesCreate'])->name('admin-sequences-create');
    Route::post('/admin/sequences-data', [SequenceController::class, 'AdminGetSequences'])->name('admin-sequences-data');
    Route::get('/admin/sequences-edit/{id}', [SequenceController::class, 'edit'])->name('admin-sequences-edit');

    Route::get('/contacts', [ContactController::class, 'index'])->name('admin-contacts-index');
    Route::get('/contacts/create', [ContactController::class,'create'])->name('admin-contacts-create');
    Route::post('/contacts/store', [ContactController::class,'store'])->name('admin-contacts-store');

    Route::get('/admin/sequences-details/{id}', [SequenceController::class, 'getSequenceDetails'])->name('admin-sequences-details');
});

Route::post('/logout', [AuthLoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');







