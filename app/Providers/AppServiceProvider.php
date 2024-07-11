<?php

namespace App\Providers;

use App\Application\Services\AuthService;
use App\Application\Services\SimulationService;
use App\Domains\Interfaces\Repositories\IUserRepository;
use App\Domains\Interfaces\Services\IAuthService;
use App\Domains\Interfaces\Services\ISimulationService;
use App\Infrastructure\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IAuthService::class, AuthService::class);
        $this->app->bind(ISimulationService::class, SimulationService::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'Database\\Factories\\' . class_basename($modelName) . 'Factory';
        });
        Factory::guessModelNamesUsing(function ($string) {
            return 'App\\Infrastructure\\Database\\Models\\' . str_replace('Factory', '', class_basename($string));
        });
    }
}
