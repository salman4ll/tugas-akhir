<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShippingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/payment-summary', function () {
    return view('product.payment_summary');
});

Route::get('/payment-summary/{layananId}', [OrderController::class, 'index'])->name('payment.summary')->middleware('auth');
Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout')->middleware('auth');

Route::post('/generate-order', [OrderController::class, 'generateOrder'])->name('generate.order')->middleware('auth');

Route::get('/detail_product/{id}', [ProductController::class, 'detail'])->name('products.detail')->middleware('auth');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::get('/provinces', [AddressController::class, 'getProvinces'])->name('provinces.index');
Route::get('/cities/{provinceId}', [AddressController::class, 'getCities'])->name('cities.index');
Route::get('/districts/{cityId}', [AddressController::class, 'getDistricts'])->name('districts.index');
Route::get('/subdistricts/{districtId}', [AddressController::class, 'getSubDistricts'])->name('subdistricts.index');

Route::get('/shipping-methods', [ShippingController::class, 'getCourierList'])->name('shipping.methods');