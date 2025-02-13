<?php

use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Middleware\Authentication;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware(Authentication::class);
