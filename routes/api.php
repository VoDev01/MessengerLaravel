<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\APIController;
use App\Http\Controllers\API\V1\APIAuthController;
use App\Http\Controllers\API\V1\ChatAPIController;
use App\Http\Controllers\API\V1\ChatBuilderAPIController;

Route::middleware(['api'])->prefix("v1")->group(function ()
{
    Route::get("/", [APIController::class, "index"])->withoutMiddleware(["api"])->middleware(['web']);
    Route::get('auth/register', [APIAuthController::class, "register"])->withoutMiddleware(["api"])->middleware(['web']);

    Route::middleware(["auth:api"])->group(function ()
    {
        Route::controller(APIAuthController::class)->prefix('auth')->group(function ()
        {
            Route::post('register', 'postRegister')->withoutMiddleware(["auth:api"]);
            Route::post('login', 'login')->withoutMiddleware(["auth:api"]);
            Route::post('logout', 'logout');
            Route::post('refresh', 'refresh');
            Route::post('me', 'me');
        });

        Route::controller(ChatBuilderAPIController::class)->prefix('chat/builder')->group(function () {
            Route::get('/', 'index');
            Route::post('/create', 'store');
            Route::get('/{id}', 'show');
            Route::put('/update/{id}', 'update');
            Route::delete('/delete/{id}', 'destroy');
        });

        Route::controller(ChatAPIController::class)->prefix('chat/{id}')->group(function () {
            Route::get('/', 'index');
            Route::get('/{message_id}', 'show');
            Route::post('/create', 'store');
            Route::put('/update/{message_id}', 'update');
            Route::delete('/delete/{message_id}', 'destroy');
        });
    });
});
