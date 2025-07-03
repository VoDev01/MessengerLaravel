<?php

use App\Http\Controllers\User\UserAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->middleware('auth')->name('home');
Route::controller(UserAuthController::class)->group(function (){
    Route::post('login', 'login');
    Route::post('register', 'register');
});