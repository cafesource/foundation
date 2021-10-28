<?php

namespace Cafesource\Foundation;

use Livewire\Livewire;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container as Application;
use Cafesource\Foundation\Facades\Cafesource as Foundation;

class CafesourceServiceProvider extends ServiceProvider
{
    protected string $config = __DIR__ . '/../config/cafesource.php';

    /**
     * @return void
     */
    public function register() : void
    {
        $this->mergeConfig();

        $this->registerManager($this->app);
        $this->registerBindings($this->app);

        $this->mergeServiceProviders(Foundation::serviceProviders());
    }

    public function boot() : void
    {
        $this->loadRoutes();

        $this->publishes([
            $this->config => config_path('cafesource.php')
        ]);

        $this->loadLivewireComponents();
        $this->loadViewComponents();
    }

    /**
     * @param Application $app
     */
    protected function registerManager( Application $app ) : void
    {
        $app->singleton('cafesource.foundation', function ( $app ) {
            return new App($app[ 'config' ][ 'cafesource' ]);
        });
    }

    /**
     * @param Application $app
     */
    protected function registerBindings( Application $app ) : void
    {
        $app->bind('cafesource.foundation', function ( $app ) {
            return new App($app[ 'config' ][ 'cafesource' ]);
        });
    }

    /**
     * @param $serviceProviders
     */
    public function mergeServiceProviders( $serviceProviders ) : void
    {
        foreach ( $serviceProviders as $provider ) {
            $this->app->register($provider);
        }
    }

    /**
     * Merge config
     */
    protected function mergeConfig() : void
    {
        $this->mergeConfigFrom($this->config, 'cafesource');
    }

    /**
     * Loads the admin livewire components
     */
    private function loadLivewireComponents() : void
    {
        foreach ( Foundation::livewireComponents() as $alias => $component ) {
            Livewire::component($alias, $component);
        }
    }

    /**
     * Load the view components
     */
    private function LoadViewComponents() : void
    {
        foreach ( Foundation::viewComponents() as $alias => $component ) {
            if ( $alias === 'namespace' ) {
                $this->loadViewComponentNamespace($component);
                continue;
            }

            Blade::component($alias, $component);
        }
    }

    /**
     * @param $components
     */
    private function loadViewComponentNamespace( $components ) : void
    {
        foreach ( $components as $namespace => $component ) {
            Blade::componentNamespace($namespace, $component);
        }
    }

    /**
     * Load the routes
     */
    public function loadRoutes() : void
    {
        foreach ( Foundation::routes() as $value ) {
            $route = Route::prefix($value[ 'prefix' ]);
            if ( isset($value[ 'middleware' ]) )
                $route->middleware($value[ 'middleware' ]);

            if ( isset($value[ 'name' ]) )
                $route->name($value[ 'name' ]);

            if ( isset($value[ 'namespace' ]) )
                $route->namespace($value[ 'namespace' ]);

            $route->group($value[ 'path' ]);
        }
    }
}
