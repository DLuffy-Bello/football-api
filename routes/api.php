<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompetitionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware(['auth:api', 'role:user'])->group(function () {
    Route::get('/competitions/{id}', [CompetitionController::class, 'getCompetition']);
});

Route::middleware('custom.api.auth')->group(function () {
    Route::get('/competitions', [CompetitionController::class, 'getCompetitions']);

    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
    });
});
