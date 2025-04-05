<?php

use App\Http\Controllers\Api\v1\Comment\CommentController;
use App\Http\Middleware\Authentication;
use Illuminate\Support\Facades\Route;

Route::middleware(Authentication::class)->group(function () {
    Route::post('', [CommentController::class, 'createComment']);
    Route::get('', [CommentController::class, 'getListComments']);
    Route::delete('', [CommentController::class, 'deleteComment']);
});

