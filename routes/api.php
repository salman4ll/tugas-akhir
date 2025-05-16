<?php

use App\Http\Controllers\Api\BiteShipController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth.sanctum.api')->post('/courier', [BiteShipController::class, 'getCourier'])->name('api.courier');
Route::post('/callback', [OrderController::class, 'callback'])->name('api.callbackMidtrans');