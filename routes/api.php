<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompetitionController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware(['auth:api', 'permission:view_details_competitions'])->group(function () {
    Route::get('/competitions/{id}', [CompetitionController::class, 'getCompetition']);
});

Route::middleware('auth:api')->group(function () {
    Route::get('/competitions', [CompetitionController::class, 'getCompetitions']);

    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
    });
});
