<?php

namespace App\Providers;

use App\Clients\Contracts\DeputadoClientInterface;
use Illuminate\Support\ServiceProvider;

use App\Clients\DeputadoClient;
use App\Repositories\Contracts\DeputadoRepositoryInterface;
use App\Repositories\Contracts\DespesaRepositoryInterface;
use App\Repositories\DeputadoRepository;
use App\Repositories\DespesaRepository;
use App\Services\Contracts\DeputadoServiceInterface;
use App\Services\DeputadoService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DeputadoClientInterface::class, DeputadoClient::class);
        $this->app->bind(DeputadoRepositoryInterface::class, DeputadoRepository::class);
        $this->app->bind(DespesaRepositoryInterface::class, DespesaRepository::class);
        $this->app->bind(DeputadoServiceInterface::class, DeputadoService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
