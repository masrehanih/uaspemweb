<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\Api\PasienController;

Route::apiResource('books', BookController::class);
Route::get('/pasien', [PasienController::class, 'index']);
Route::get('test-books-route', function () {
    return 'Books route works!';
});