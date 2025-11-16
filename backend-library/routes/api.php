<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Book API Routes
Route::apiResource('books', BookController::class);

// Additional Book Routes
Route::prefix('books')->group(function () {
    Route::patch('/{id}/status', [BookController::class, 'updateStatus']);
    Route::get('/category/{category}', [BookController::class, 'getByCategory']);
    Route::get('/statistics', [BookController::class, 'getStatistics']);
});