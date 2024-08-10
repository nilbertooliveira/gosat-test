<?php

use App\Application\Controllers\AuthController;
use App\Application\Controllers\HomeController;
use App\Application\Controllers\SimulationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Liquid\Template;

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

Route::middleware('auth:sanctum')
    ->get('/chart', [SimulationController::class, 'chart'])
    ->name('simulation.chart');


Route::get('/template', function () {
    $data = [
        'nome' => 'Nilberto',
        'telefone' => 31984126450,
        'idade' => 35,
        'sexo' => 'M',
        'pais' => 'Brasil',
        'estado' => 'Minas gerais',
        'cidade' => 'Belo Horizonte',
        'bairro' => 'Nova Gameleira',
        'rua' => 'Cândido de Souza',
        'numero' => 994,
    ];
    $variablesLiquid = array_keys($data);

    return view('template-sms', compact('data', 'variablesLiquid'));
});

Route::post('/render', function (Request $request) {
    $template = $request->validate(['template' => 'required|string']);
    $data = $request->data;

    $engine = new Template();
    $engine->parse($template['template']);

    return $engine->render($data);
});
