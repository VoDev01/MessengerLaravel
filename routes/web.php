<?php

use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\User\UserAuthController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function ()
{
    Route::controller(UserController::class)->middleware(['auth'])->group(function ()
    {
        Route::get('/', 'home')->name('home');
        Route::get('profile', 'profile');
        Route::get('settings', 'settings');
    });
    Route::controller(ChatController::class)->prefix('chat/{chat}')->middleware(['auth'])->group(function()
    {
        Route::get('index', 'index');
        Route::post('store', 'store');
    });
    Route::controller(UserAuthController::class)->group(function ()
    {
        Route::get('login', 'login')->name('login');
        Route::post('postLogin', 'postLogin');
        Route::get('register', 'register');
        Route::post('postRegister', 'postRegister');
    });
});
