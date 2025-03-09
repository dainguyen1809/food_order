<?php

use App\Http\Controllers\Api\v1\Discount\DiscountController;
use App\Http\Middleware\Authentication;
use Illuminate\Support\Facades\Route;

Route::get('/details/{discount_id}', [DiscountController::class, 'discountDetails']);
Route::get('/lists', [DiscountController::class, 'getListDiscountWithProduct']);
Route::post('/apply', [DiscountController::class, 'userApplyDiscount']);
Route::post('/cancel', [DiscountController::class, 'userCanceledDiscount']);

Route::middleware(Authentication::class)->group(function () {
    Route::post('', [DiscountController::class, 'createNewDiscount']);
    Route::get('/shop', [DiscountController::class, 'getListDiscountByShop']);
    Route::patch('{discount_id}', [DiscountController::class, 'updateDiscountByID']);
    Route::delete('/delete/{discount_id}', [DiscountController::class, 'deleteDiscountByID']);
});

