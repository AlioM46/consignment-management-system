<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DeliveryItemController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleItemController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\Web\AuthController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', fn() => redirect('/dashboard'));
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('vendors', VendorController::class);
    Route::resource('drivers', DriverController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::resource('products', ProductController::class);
    Route::resource('deliveries', DeliveryController::class);
    Route::resource('delivery-items', DeliveryItemController::class);
    Route::resource('sales', SaleController::class);
    Route::resource('sale-items', SaleItemController::class);

    Route::resource('invoices', InvoiceController::class)->except(['store']);
    Route::post('invoices', [InvoiceController::class, 'createInvoicePerVehicle'])->name('invoices.store');
});
