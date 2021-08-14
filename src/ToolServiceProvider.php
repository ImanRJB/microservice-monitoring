<?php

namespace Milyoona\MicroserviceMonitor;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Milyoona\MicroserviceMonitor\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/microservice-monitor.php' => config_path('microservice-monitor.php'),
        ], 'microservice-monitor');

        $this->publishes([
            __DIR__.'/../database/migrations/2021_08_04_145607_create_microservices_table.php' => database_path('migrations/2021_08_04_145607_create_microservices_table.php'),
        ], 'microservice-monitor');

        $this->publishes([
            __DIR__.'/../database/seeders/MicroserviceSeeder.php' => database_path('seeders/MicroserviceSeeder.php'),
        ], 'microservice-monitor');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'microservice-monitor');

        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            //
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
                ->prefix('nova-vendor/milyoona/microservice-monitor')
                ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
