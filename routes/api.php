<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // 1. АВТОРИЗАЦІЯ (Login)
    Route::post('/login', [AuthController::class, 'login']);

    // 2. РЕСУРСИ, ДОСТУПНІ ТІЛЬКИ АВТОРИЗОВАНИМ КОРИСТУВАЧАМ
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);

        /**
         * GET /users          -> UserController@index   (Список)
         * POST /users         -> UserController@store   (Створення)
         * GET /users/{id}     -> UserController@show    (Деталі одного)
         * PUT/PATCH /users/{id}-> UserController@update  (Редагування)
         * DELETE /users/{id}  -> UserController@destroy (Видалення)
         */
        Route::apiResource('users', UserController::class);
    });
});
