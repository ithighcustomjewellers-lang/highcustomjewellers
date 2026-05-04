<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController as AuthLoginController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\SequenceController;
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
    Route::get('/user-profile', [DashboardController::class, 'UserProfile'])->name('user-profile');
    Route::post('/submit-profile-update', [DashboardController::class, 'submitProfileUpdate'])->name('submit-profile-update');

    Route::get('/connect-gmail', [GoogleController::class, 'redirect']);
    Route::get('/google/callback', [GoogleController::class, 'callback']);
});

// admin info
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'AdminDashboard'])->name('admin-dashboard');

    Route::get('/users', [AdminDashboardController::class, 'AdminUsers'])->name('admin-users-index');
    Route::post('/users/data', [AdminDashboardController::class, 'AdminGetUsers'])->name('admin-users-data');

    Route::get('/users/{id}', [AdminDashboardController::class, 'updateUser'])->name('updateUser');
    Route::post('/users/update', [AdminDashboardController::class, 'adminUpdateData'])->name('admin-update-data');

    Route::delete('/users/destroy', [AdminDashboardController::class, 'adminDestroyUser'])->name('admin-users-destroy');

    Route::get('/profile', [AdminDashboardController::class, 'adminProfile'])->name('admin-profile');
    Route::post('/admin-submit', [AdminDashboardController::class, 'adminUpdateDataUpdate'])->name('admin-submit-profile-update');

    Route::get('/sequences', [SequenceController::class, 'sequencesIndex'])->name('admin-sequences-index');
    Route::get('/sequences/create', [SequenceController::class, 'sequencesCreate'])->name('admin-sequences-create');
    Route::post('/sequences/store', [SequenceController::class, 'sequencesStore'])->name('admin-sequences-store');
    Route::post('/admin/sequences-data', [SequenceController::class, 'AdminGetSequences'])->name('admin-sequences-data');
    Route::get('/admin/sequences-edit/{id}', [SequenceController::class, 'edit'])->name('admin-sequences-edit');

    Route::get('/contacts', [ContactController::class, 'index'])->name('admin-contacts-index');
    Route::get('/contacts/create', [ContactController::class,'create'])->name('admin-contacts-create');
    Route::post('/contacts/store', [ContactController::class,'store'])->name('admin-contacts-store');
    // Route::get('/campaign/start/{id}', [CampaignController::class, 'start'])->name('campaign.start');



    Route::get('/admin/sequences-details/{id}', [SequenceController::class, 'getSequenceDetails'])->name('admin-sequences-details');
});

Route::post('/logout', [AuthLoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');







