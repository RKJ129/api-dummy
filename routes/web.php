<?php

// use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BookController;
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;

// Route::prefix('api')->group(function() {
//     Route::apiResource('books', BookController::class);
//     Route::post('books/{book}/ratings', [RatingController::class, 'store']);
// });

Route::get('/', function () {
    return view('welcome');
});
