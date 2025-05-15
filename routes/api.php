<?php

use App\Http\Controllers\Api\BiteShipController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth.sanctum.api')->post('/courier', [BiteShipController::class, 'getCourier'])->name('api.courier');