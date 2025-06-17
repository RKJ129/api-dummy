<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::apiResource('books', BookController::class);
Route::post('books/{book}/ratings', [RatingController::class, 'store']);

// Route::apiResource('todo', TodoController::class);

Route::post('register',RegisterController::class);
Route::post('login', LoginController::class);
Route::post('logout', LogoutController::class);

Route::middleware('auth:api')->get('user', function (Request $request){
    return $request->user();
});

Route::middleware('auth:api')->group(function () {
    Route::apiResource('todo', TodoController::class);
    Route::post('todo/like/{todo}', [TodoController::class, 'like']);
    Route::post('todo/dislike/{todo}', [TodoController::class, 'dislike']);
    Route::post('todo/comment/{todo}', [TodoController::class, 'comment']);

    Route::get('/profile', [ProfileController::class, 'index']);
});

