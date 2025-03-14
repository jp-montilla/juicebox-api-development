<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WeatherController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/weather',[WeatherController::class, 'getWeather']);

Route::middleware('auth:sanctum')->group(function(){
    Route::resource('posts', PostController::class);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/users/{id}', [UserController::class, 'show']);
});


