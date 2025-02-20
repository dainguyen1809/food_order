<?php

use App\Http\Controllers\Api\v1\Product\ProductController;
use App\Http\Middleware\Authentication;
use Illuminate\Support\Facades\Route;

Route::middleware(Authentication::class)->group(function () {
    Route::post('', [ProductController::class, 'createProduct']);
});

