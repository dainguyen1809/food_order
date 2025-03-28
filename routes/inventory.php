<?php

use App\Http\Controllers\Api\v1\Inventory\InventoryController;
use App\Http\Middleware\Authentication;
use Illuminate\Support\Facades\Route;

Route::middleware(Authentication::class)->group(function () {
    Route::post('', [InventoryController::class, 'addStockToInventory']);
});

