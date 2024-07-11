<?php

use App\Application\Controllers\AuthController;
use App\Application\Controllers\SimulationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum')->name('user');

Route::post('/v1/auth/custom-login', [AuthController::class, 'customLogin'])->name('auth.login-custom');

Route::middleware('auth:sanctum')->prefix('/v1/simulation')->group(function () {
    Route::post('/credit', [SimulationController::class, 'credit'])->name('simulation.credit');
    Route::post('/offer', [SimulationController::class, 'offer'])->name('simulation.offer');
    Route::post('/calculate', [SimulationController::class, 'calculate'])->name('simulation.calculate');
});

