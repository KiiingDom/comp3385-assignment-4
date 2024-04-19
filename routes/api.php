<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/v1/login', [AuthController::class, 'login']);
Route::post('/v1/logout', [AuthController::class, 'logout']);

Route::group(['prefix' => 'v1'], function () {
    Route::get('/movies', [MovieController::class, 'index'])->middleware('auth:api');
    Route::post('/movies', [MovieController::class, 'store'])->middleware('auth:api');
});