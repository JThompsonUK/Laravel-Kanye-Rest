<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\TokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckApiCredentials;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(CheckApiCredentials::class)->group(function () {
    Route::get('/data', [ApiController::class, 'getData']);
    Route::get('/refresh-data', [ApiController::class, 'refreshData']);
});

Route::post('get-token', [TokenController::class, 'getToken']);
