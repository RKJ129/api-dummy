<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::apiResource('books', BookController::class);
Route::post('books/{book}/ratings', [RatingController::class, 'store']);

Route::apiResource('todo', TodoController::class);