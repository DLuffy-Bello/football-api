<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompetitionController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::prefix('competitions')->group(function () {
    Route::get('', [CompetitionController::class, 'getCompetitions']);
    Route::get('/{id}', [CompetitionController::class, 'getCompetition']);
});

Route::prefix('teams')->group(function () {
    Route::get('', [\App\Http\Controllers\TeamController::class, 'getTeams']);
    Route::get('/{id}', [\App\Http\Controllers\TeamController::class, 'getTeam']);
});

Route::prefix('players')->group(function () {
    Route::get('', [\App\Http\Controllers\PlayerController::class, 'getPlayers']);
    Route::get('/{id}', [\App\Http\Controllers\PlayerController::class, 'getPlayer']);
});
