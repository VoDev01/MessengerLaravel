<?php

use App\Http\Controllers\User\UserAuthController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->middleware(['auth', 'web'])->group(function (){
    Route::get('home', 'home')->name('home');
});
Route::controller(UserAuthController::class)->group(function (){
    Route::get('login', 'login')->name('login');
    Route::post('postLogin', 'postLogin');
    Route::get('register', 'register');
    Route::post('postRegister', 'postRegister');
});