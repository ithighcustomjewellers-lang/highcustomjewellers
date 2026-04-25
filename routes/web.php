<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController as AuthLoginController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use Illuminate\Support\Facades\Route;

// user info
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthLoginController::class, 'Register'])->name('register-data');
    Route::post('/register', [AuthLoginController::class, 'SubmitRegister'])->name('submit_register');

    Route::get('/', [AuthLoginController::class, 'Login'])->name('login-data');
    Route::post('/login', [AuthLoginController::class, 'SubmitLogin'])->name('submit_login');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

    Route::post('/logout', [AuthLoginController::class, 'logout'])->name('logout');

});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'Dashboard'])->name('dashboard');
    Route::get('/user-profile', [DashboardController::class, 'UserProfile'])->name('user-profile');
    Route::post('/submit-profile-update', [DashboardController::class, 'submitProfileUpdate'])->name('submit-profile-update');
});



// admin info
Route::middleware('guest:admin')->group(function () {
Route::get('/admin/login', [AdminLoginController::class, 'AdminLogin'])->name('admin-login');
Route::post('/admin/login', [AdminLoginController::class, 'AdminSubmitLogin'])->name('admin-submit-login');

Route::get('/admin/forgot-password', [AdminLoginController::class, 'adminShowForgotForm'])->name('admin.password.request');
Route::post('/admin/forgot-password', [AdminLoginController::class, 'adminSendResetLink'])->name('admin.password.email');

Route::get('/admin/reset-password/{token}', [AdminLoginController::class, 'adminShowResetForm'])->name('admin.password.reset');
Route::post('/admin/reset-password', [AdminLoginController::class, 'adminResetPassword'])->name('admin.password.update');

});

Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'AdminDashboard'])->name('admin-dashboard');

    Route::get('/users', [AdminDashboardController::class, 'AdminUsers'])->name('admin-users-index');
    Route::post('/users/data', [AdminDashboardController::class, 'AdminGetUsers'])->name('admin-users-data');

    Route::get('/users/{id}', [AdminDashboardController::class, 'updateUser'])->name('updateUser');
    Route::post('/users/update', [AdminDashboardController::class, 'adminUpdateData'])->name('admin-update-data');

    Route::delete('/users/destroy', [AdminDashboardController::class, 'adminDestroyUser'])->name('admin-users-destroy');

    Route::get('/admin/logout', [AdminLoginController::class, 'AdminLogout'])->name('admin-logout');
});


