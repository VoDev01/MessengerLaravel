<?php

use App\Http\Controllers\User\UserAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->middleware('auth')->name('home');
Route::controller(UserAuthController::class)->group(function (){
    Route::get('login', 'login')->name('login');
    Route::post('postLogin', 'postLogin');
    Route::get('register', 'register');
    Route::post('postRegister', 'postRegister');
});