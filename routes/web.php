<?php

use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::get('/register', [LoginController::class, 'Register'])->name('register-data');
    Route::post('/register', [LoginController::class, 'SubmitRegister'])->name('submit_register');

    Route::get('/login', [LoginController::class, 'Login'])->name('login-data');
    Route::post('/login', [LoginController::class, 'SubmitLogin'])->name('submit_login');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'Dashboard'])->name('dashboard');
    Route::get('/user-profile', [DashboardController::class, 'UserProfile'])->name('user-profile');
    Route::post('/submit-profile-update', [DashboardController::class, 'submitProfileUpdate'])->name('submit-profile-update');
});
