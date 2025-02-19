<?php

use App\Http\Controllers\Api\v1\Product\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('', [ProductController::class, 'createProduct']);

