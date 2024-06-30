<?php

use App\Http\Controllers\Api\V1\CompletedTaskController;
use App\Http\Controllers\Api\V1\PriorityController;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Middleware\CheckTokenExpiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix("v1")->group(function() {
    Route::post("/register", [AuthController::class, "register"]);
    Route::post("/login", [AuthController::class, "login"]);

    Route::middleware('auth:sanctum', CheckTokenExpiry::class)->group(function() {
        Route::apiResource('/tasks', TaskController::class);
        Route::patch('/tasks/{id}/complete', CompletedTaskController::class);
        Route::apiResource('/priorities', PriorityController::class);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
