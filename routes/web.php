<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\BiteShipController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShippingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/user/pesanan', [OrderController::class, 'getOrder'])
    ->name('user.pesanan')
    ->middleware('auth');

Route::get('/user/pesanan/detail/{id}', [OrderController::class, 'getOrderDetail'])
    ->name('user.pesanan.detail')
    ->middleware('auth');

Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])
    ->name('register')
    ->middleware('guest');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

Route::get('/payment-summary', function () {
    return view('product.payment_summary');
});

Route::get('/payment-summary/{layananId}', [OrderController::class, 'index'])
    ->name('payment.summary')
    ->middleware('auth');
Route::post('/checkout', [OrderController::class, 'checkout'])
    ->name('checkout')
    ->middleware('auth');

Route::post('/generate-order', [OrderController::class, 'generateOrder'])
    ->name('generate.order')
    ->middleware('auth');

Route::get('/detail_product/{id}', [ProductController::class, 'detail'])
    ->name('products.detail')
    ->middleware('auth');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::get('/provinces', [AddressController::class, 'getProvinces'])->name('provinces.index');
Route::get('/cities/{provinceId}', [AddressController::class, 'getCities'])->name('cities.index');
Route::get('/districts/{cityId}', [AddressController::class, 'getDistricts'])->name('districts.index');
Route::get('/subdistricts/{districtId}', [AddressController::class, 'getSubDistricts'])->name('subdistricts.index');

Route::get('/shipping-methods', [ShippingController::class, 'getCourierList'])->name('shipping.methods');


Route::get('/user/dashboard', [DashboardController::class, 'index'])
    ->name('user.dashboard')
    ->middleware('auth');

Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard')->middleware(['auth:admin', 'role:logistik,am']);

Route::get('admin/orders/{type}', [AdminOrderController::class, 'index'])->name('admin.orders')->middleware(['auth:admin', 'role:logistik']);

Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::post('/admin/orders/update-status/{id}', [AdminOrderController::class, 'updateStatus'])->name('admin.pesanan.updateStatus');

Route::get('/admin/ekspedisi', [AdminOrderController::class, 'getMetodePengiriman'])
    ->name('admin.get-metode-pengiriman')->middleware(['auth:admin', 'role:logistik']);

Route::get('/admin/ekspedisi/create', [AdminOrderController::class, 'create'])
    ->name('admin.ekspedisi.create')
    ->middleware(['auth:admin', 'role:logistik']);

Route::post('/admin/ekspedisi/store', [AdminOrderController::class, 'store'])
    ->name('admin.ekspedisi.store')
    ->middleware(['auth:admin', 'role:logistik']);

Route::get('/admin/ekspedisi/edit/{id}', function ($id) {
    return view('components.label_shipping', ['id' => $id]);
})->name('admin.ekspedisi.edit')->middleware(['auth:admin', 'role:logistik']);

Route::get('/{orderId}/download-label', [AdminOrderController::class, 'downloadLabelShipping'])
    ->name('downloadLabel')->middleware(['auth:admin', 'role:logistik']);
