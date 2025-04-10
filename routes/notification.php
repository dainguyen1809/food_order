<?php

use App\Http\Controllers\Api\v1\Notification\NotificationController;
use App\Http\Middleware\Authentication;
use Illuminate\Support\Facades\Route;

Route::middleware(Authentication::class)->group(function () {
    Route::get('', [NotificationController::class, 'getListNotifyByUser']);
});

