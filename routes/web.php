<?php

use App\Application\Controllers\AuthController;
use App\Application\Controllers\HomeController;
use App\Application\Controllers\SimulationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')
    ->post('v1/auth/login', [AuthController::class, 'login'])
    ->name('v1.auth.login');


Route::middleware('auth:sanctum')
    ->get('/credit', [SimulationController::class, 'creditView'])
    ->name('simulation.credit-view');
