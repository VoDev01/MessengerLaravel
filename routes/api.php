<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\ChatAPIController;

Route::middleware(['api'])->prefix("v1")->group(function ()
{
    Route::get("/", [ChatAPIController::class, "index"]);

    Route::post('/tokens/create', function (Request $request)
    {
        $token = $request->user()->createToken($request->token_name);

        return ['token' => $token->plainTextToken];
    });
});
