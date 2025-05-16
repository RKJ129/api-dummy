<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;

Route::apiResource('books', BookController::class);
Route::post('books/{book}/ratings', [RatingController::class, 'store']);