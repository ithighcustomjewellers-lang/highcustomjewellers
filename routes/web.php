<?php

use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/register',[LoginController::class, 'Register'])->name('register-data');
Route::post('/register',[LoginController::class, 'SubmitRegister'])->name('submit_register');

Route::get('/login',[LoginController::class, 'Login'])->name('login-data');
Route::post('/login',[LoginController::class, 'SubmitLogin'])->name('submit_login');

Route::get('/dashboard',[DashboardController::class, 'Dashboard'])->name('dashboard');
