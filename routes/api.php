<?php

use App\Http\Controllers\API\V1\ChatAPIController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])->prefix("v1")->group(function (){
    Route::get("/", [ChatAPIController::class, "index"]);
});