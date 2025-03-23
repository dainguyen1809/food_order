<?php

use App\Http\Controllers\Api\v1\Orders\CheckoutController;
use App\Http\Middleware\Authentication;
use Illuminate\Support\Facades\Route;

Route::middleware(Authentication::class)->group(function () {
    Route::post('/preview', [CheckoutController::class, 'checkoutPreview']);

});

