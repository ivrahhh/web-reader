<?php

use App\Http\Controllers\Authentication\LoginController;
use App\Http\Controllers\Authentication\SignupController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| Here is where you can register authentication routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class,'create'])->name('login');
    Route::post('login', [LoginController::class,'store'])->name('authenticate');

    Route::get('create-account', [SignupController::class,'create'])->name('signup');
    Route::post('create-account', [SignupController::class,'store'])->name('signup.store');
});