<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Resources\UserResource;

Route::prefix('v1')->group(function () {
    require __DIR__ . '/auth.php';


    Route::middleware(['auth:sanctum'])->group(function () {

        Route::get('/me', [UserController::class, 'me']);

        Route::apiResource('departments', \App\Http\Controllers\DepartmentController::class);

        Route::apiResource('shifts', \App\Http\Controllers\ShiftController::class);

        Route::apiResource('schedules', \App\Http\Controllers\ScheduleController::class);
    });
});
