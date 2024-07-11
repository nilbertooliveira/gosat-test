<?php

namespace App\Providers;

use App\Application\Services\AuthService;
use App\Application\Services\GraphicalReportService;
use App\Application\Services\InstitutionService;
use App\Application\Services\ModalityService;
use App\Application\Services\SimulationService;
use App\Domains\Interfaces\Repositories\IInstitutionRepository;
use App\Domains\Interfaces\Repositories\IModalityRepository;
use App\Domains\Interfaces\Repositories\ISimulationRepository;
use App\Domains\Interfaces\Repositories\IUserRepository;
use App\Domains\Interfaces\Services\IAuthService;
use App\Domains\Interfaces\Services\IGraphicalReportService;
use App\Domains\Interfaces\Services\IInstitutionService;
use App\Domains\Interfaces\Services\IModalityService;
use App\Domains\Interfaces\Services\ISimulationService;
use App\Infrastructure\Repositories\InstitutionRepository;
use App\Infrastructure\Repositories\ModalityRepository;
use App\Infrastructure\Repositories\SimulationRepository;
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
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IInstitutionService::class, InstitutionService::class);
        $this->app->bind(IInstitutionRepository::class, InstitutionRepository::class);
        $this->app->bind(IModalityService::class, ModalityService::class);
        $this->app->bind(IModalityRepository::class, ModalityRepository::class);
        $this->app->bind(ISimulationService::class, SimulationService::class);
        $this->app->bind(ISimulationRepository::class, SimulationRepository::class);
        $this->app->bind(IGraphicalReportService::class, GraphicalReportService::class);
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
