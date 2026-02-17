<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DeliveryItemController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleItemController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VendorController;

Route::get('/ping', fn() => response()->json(['pong' => true]));

// --------------------
// Auth (PUBLIC)
// --------------------
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

// --------------------
// Auth (PROTECTED)
// --------------------
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);

    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

// =========================================================
// Resources (PROTECTED) - add/remove middleware as you want
// =========================================================

// Vendors
Route::prefix('vendors')->middleware('auth:api')->group(function () {
    Route::get('', [VendorController::class, 'index']);
    Route::post('', [VendorController::class, 'store']);
    Route::get('{id}', [VendorController::class, 'show']);
    Route::match(['put', 'patch'], '{id}', [VendorController::class, 'update']);
    Route::delete('{id}', [VendorController::class, 'destroy']);
});

// Drivers
Route::prefix('drivers')->middleware('auth:api')->group(function () {
    Route::get('', [DriverController::class, 'index']);
    Route::post('', [DriverController::class, 'store']);
    Route::get('{id}', [DriverController::class, 'show']);
    Route::match(['put', 'patch'], '{id}', [DriverController::class, 'update']);
    Route::delete('{id}', [DriverController::class, 'destroy']);
});

// Vehicles
Route::prefix('vehicles')->middleware('auth:api')->group(function () {
    Route::get('', [VehicleController::class, 'index']);
    Route::post('', [VehicleController::class, 'store']);
    Route::get('{id}', [VehicleController::class, 'show']);
    Route::match(['put', 'patch'], '{id}', [VehicleController::class, 'update']);
    Route::delete('{id}', [VehicleController::class, 'destroy']);
});

// Products
Route::prefix('products')->middleware('auth:api')->group(function () {
    Route::get('', [ProductController::class, 'index']);
    Route::post('', [ProductController::class, 'store']);
    Route::get('{id}', [ProductController::class, 'show']);
    Route::match(['put', 'patch'], '{id}', [ProductController::class, 'update']);
    Route::delete('{id}', [ProductController::class, 'destroy']);
});

// Deliveries
Route::prefix('deliveries')->middleware('auth:api')->group(function () {
    Route::get('', [DeliveryController::class, 'index']);
    Route::post('', [DeliveryController::class, 'store']);
    Route::get('{id}', [DeliveryController::class, 'show']);
    Route::match(['put', 'patch'], '{id}', [DeliveryController::class, 'update']);
    Route::delete('{id}', [DeliveryController::class, 'destroy']);
});

// Delivery Items
Route::prefix('delivery-items')->middleware('auth:api')->group(function () {
    Route::get('', [DeliveryItemController::class, 'index']);
    Route::post('', [DeliveryItemController::class, 'store']);
    Route::get('{id}', [DeliveryItemController::class, 'show']);
    Route::match(['put', 'patch'], '{id}', [DeliveryItemController::class, 'update']);
    Route::delete('{id}', [DeliveryItemController::class, 'destroy']);
});

// Sales
Route::prefix('sales')->middleware('auth:api')->group(function () {
    Route::get('', [SaleController::class, 'index']);
    Route::post('', [SaleController::class, 'store']);
    Route::get('{id}', [SaleController::class, 'show']);
    Route::match(['put', 'patch'], '{id}', [SaleController::class, 'update']);
    Route::delete('{id}', [SaleController::class, 'destroy']);
});

// Sale Items
Route::prefix('sale-items')->middleware('auth:api')->group(function () {
    Route::get('', [SaleItemController::class, 'index']);
    Route::post('', [SaleItemController::class, 'store']);
    Route::get('{id}', [SaleItemController::class, 'show']);
    Route::match(['put', 'patch'], '{id}', [SaleItemController::class, 'update']);
    Route::delete('{id}', [SaleItemController::class, 'destroy']);
});

// Invoices
Route::prefix('invoices')->middleware('auth:api')->group(function () {
    Route::get('', [InvoiceController::class, 'index']);
    Route::post('', [InvoiceController::class, 'createInvoicePerVehicle']);
    Route::get('{id}', [InvoiceController::class, 'show']);
    Route::match(['put', 'patch'], '{id}', [InvoiceController::class, 'update']);
    Route::delete('{id}', [InvoiceController::class, 'destroy']);
});
