<?php

use App\Http\Controllers\Api\BiteShipController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth.sanctum.api')->post('/courier', [BiteShipController::class, 'getCourier'])->name('api.courier');
Route::middleware(['auth.sanctum.api', 'checkRoleApi:logistik'])->post('/create-shipping', [BiteShipController::class, 'createShipping'])->name('api.createShipping');
Route::post('/callback', [OrderController::class, 'callback'])->name('api.callbackMidtrans');
Route::post('/webhook-biteship', [BiteShipController::class, 'webhookBiteship'])->name('api.webhookBiteShip');
Route::post('/get-tracking', [BiteShipController::class, 'getTracking'])->name('api.getTracking');

Route::middleware('auth.sanctum.api')->post('/confirmation-order', [OrderController::class, 'confirmationOrder'])->name('api.confirmationOrder');

Route::middleware(['auth.sanctum.api', 'checkRoleApi:am,logistik'])->get('/orders/{id}', [OrderController::class, 'getOrderDetail'])->name('api.orderDetail');