<?php

use App\Http\Controllers\Api\v1\Cart\CartController;
use App\Http\Middleware\Authentication;
use Illuminate\Support\Facades\Route;

Route::get('', [CartController::class, 'getListCartItems']);

Route::middleware(Authentication::class)->group(function () {
    Route::post('', [CartController::class, 'addToCart']);
    Route::post('/update', [CartController::class, 'updateCart']);
    Route::post('/remove', [CartController::class, 'removeCartItem']);
});

