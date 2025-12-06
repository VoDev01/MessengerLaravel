<?php

use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\User\UserAuthController;
use App\Http\Controllers\User\UserController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->middleware(['auth'])->group(function ()
{
    Route::controller(UserController::class)->group(function ()
    {
        Route::get('/', 'home')->name('home');
        Route::get('profile', 'profile');
        Route::get('settings', 'settings');
        Route::post('logout', 'logout');
        Route::post('chats', 'chats');
    });
    Route::post("chat/create", [ChatController::class, "create"]);
    Route::controller(ChatController::class)->prefix('chat/{chat}')->group(function()
    {
        Route::get('/', 'group');
        Route::post('store', 'store');
        Route::post('join', 'join');
        Route::post('seen', 'seen')->withoutMiddleware(VerifyCsrfToken::class);
        Route::post('delivered', 'delivered')->withoutMiddleware(VerifyCsrfToken::class);
    });
    Route::get('/direct/{user}', [ChatController::class, 'direct']);
    Route::controller(UserAuthController::class)->withoutMiddleware(['auth'])->group(function ()
    {
        Route::get('login', 'login')->name('login');
        Route::post('postLogin', 'postLogin');
        Route::get('register', 'register');
        Route::post('postRegister', 'postRegister');
    });
});
