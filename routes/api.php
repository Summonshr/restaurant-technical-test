<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\DishRatingController;
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

Route::middleware(['auth:sanctum', 'throttle:20,1'])->group(function () {
    Route::resource('/dishes', DishController::class);
    Route::post('/dishes/{dish}/rate', [DishRatingController::class, 'rateDish']);
});

Route::post('/login', [AuthController::class, 'login']);
