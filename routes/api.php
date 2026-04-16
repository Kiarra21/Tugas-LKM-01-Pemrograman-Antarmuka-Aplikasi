<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BorrowingController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::middleware('role:admin,user')->group(function () {
        Route::get('/books', [BookController::class, 'index']);
        Route::get('/books/{book}', [BookController::class, 'show']);

        Route::get('/borrowings', [BorrowingController::class, 'index']);
        Route::get('/borrowings/{borrowing}', [BorrowingController::class, 'show']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::post('/books', [BookController::class, 'store']);
        Route::put('/books/{book}', [BookController::class, 'update']);
        Route::patch('/books/{book}', [BookController::class, 'update']);
        Route::delete('/books/{book}', [BookController::class, 'destroy']);

        Route::post('/borrowings', [BorrowingController::class, 'store']);
        Route::put('/borrowings/{borrowing}', [BorrowingController::class, 'update']);
        Route::patch('/borrowings/{borrowing}', [BorrowingController::class, 'update']);
        Route::delete('/borrowings/{borrowing}', [BorrowingController::class, 'destroy']);
    });
});
