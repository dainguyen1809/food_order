<?php

use App\Http\Controllers\Api\v1\Product\ProductController;
use App\Http\Middleware\Authentication;
use Illuminate\Support\Facades\Route;

Route::get('/search/{keySearch}', [ProductController::class, 'productSearchByGuest']);

Route::middleware(Authentication::class)->group(function () {
    Route::post('', [ProductController::class, 'createProduct']);
    Route::get('/draft/all', [ProductController::class, 'getAllProductDrafts']);
    Route::get('/publish/all', [ProductController::class, 'getAllProductIsPublished']);
    Route::patch('/publish/{id}', [ProductController::class, 'updateIsPublishedProduct']);
    Route::patch('/unpublish/{id}', [ProductController::class, 'updateUnPublishedProduct']);
});

