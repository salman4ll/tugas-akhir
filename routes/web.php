<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('welcome'));

// Auth: Login & Register
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/user/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');

    // Orders
    Route::get('/user/pesanan', [OrderController::class, 'getOrder'])->name('user.pesanan');
    Route::get('/user/pesanan/detail/{id}', [OrderController::class, 'getOrderDetail'])->name('user.pesanan.detail');
    Route::post('/generate-order', [OrderController::class, 'generateOrder'])->name('generate.order');
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');

    // Products
    Route::get('/detail_product/{id}', [ProductController::class, 'detail'])->name('products.detail');

    // Payment Summary
    Route::get('/payment-summary/{layananId}', [OrderController::class, 'index'])->name('payment.summary');
});

// Public Product List
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Address API
Route::prefix('')->group(function () {
    Route::get('/provinces', [AddressController::class, 'getProvinces'])->name('provinces.index');
    Route::get('/cities/{provinceId}', [AddressController::class, 'getCities'])->name('cities.index');
    Route::get('/districts/{cityId}', [AddressController::class, 'getDistricts'])->name('districts.index');
    Route::get('/subdistricts/{districtId}', [AddressController::class, 'getSubDistricts'])->name('subdistricts.index');
});

// Shipping Methods
Route::get('/shipping-methods', [ShippingController::class, 'getCourierList'])->name('shipping.methods');

// Optional (non-auth): Static payment view
Route::get('/payment-summary', fn() => view('product.payment_summary'));

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    // Admin Auth
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    });

    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // Admin Dashboard (Logistik & AM)
    Route::middleware(['auth:admin', 'role:logistik,am'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    });

    // Order Management (Logistik only)
    Route::middleware(['auth:admin', 'role:logistik'])->group(function () {
        Route::get('/orders/{type}', [AdminOrderController::class, 'index'])->name('admin.orders');
        Route::post('/orders/update-status/{id}', [AdminOrderController::class, 'updateStatus'])->name('admin.pesanan.updateStatus');
        Route::post('/orders/cancel/{id}', [AdminOrderController::class, 'canceledOrder'])->name('admin.order.cancel');

        // Ekspedisi Management
        Route::get('/ekspedisi', [AdminOrderController::class, 'getMetodePengiriman'])->name('admin.get-metode-pengiriman');
        Route::get('/ekspedisi/create', [AdminOrderController::class, 'create'])->name('admin.ekspedisi.create');
        Route::post('/ekspedisi/store', [AdminOrderController::class, 'store'])->name('admin.ekspedisi.store');

        // Edit label shipping
        Route::get('/ekspedisi/edit/{id}', fn($id) => view('components.label_shipping', ['id' => $id]))
            ->name('admin.ekspedisi.edit');
        Route::get('/{orderId}/download-label', [AdminOrderController::class, 'downloadLabelShipping'])
            ->name('admin.pesanan.downloadLabel');
    });
});

// Download Label (Admin logistik)
