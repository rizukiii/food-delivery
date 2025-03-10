<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'detail']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('user', [UserController::class, 'detail']);
    Route::put('user/update', [UserController::class, 'update']);

    Route::post('cart/add', [CartController::class, 'addToCart']);
    Route::get('cart', [CartController::class, 'getCart']);
    Route::delete('cart/{id}', [CartController::class, 'removeFromCart']);

    Route::post('order/place', [OrderController::class, 'placeOrder']);
    Route::get('orders', [OrderController::class, 'getOrders']);
    Route::put('order/{id}', [OrderController::class, 'cancelOrders']);
});
