<?php

namespace Cafesource\Foundation;

use Illuminate\Support\Arr;
use Cafesource\Foundation\Cache\Manager as CacheManager;
use Cafesource\Foundation\Autoload\LoadManager as AutoloadManager;

class App
{
    protected array $config   = [];
    protected array $autoload = [];
    protected array $cache    = [];

    public function __construct( $config )
    {
        $this->config = $config;

        $this->app = $this->autoload('app', $config);
    }

    /**
     * @return array
     */
    public function configs() : array
    {
        return $this->config;
    }

    /**
     * @return array
     */
    public function serviceProviders() : array
    {
        return $this->app->get('providers', []);
    }

    /**
     * @return array
     */
    public function routes() : array
    {
        return $this->app->get('routes', []);
    }

    /**
     * @param string $name
     * @param array  $options
     * @param        $path
     */
    public function addRoute( string $name, $path, array $options = [] ) : App
    {
        $this->app->filter('routes', function ( $routes ) use ( $name, $options, $path ) {
            $routes[ $name ] = array_merge(['path' => $path], $options);

            return $routes;
        });

        return $this;
    }

    /**
     * @return mixed|null
     */
    public function livewireComponents()
    {
        return $this->app->get('livewire_components', []);
    }

    /**
     * @param string|array $component
     * @param null         $path
     */
    public function addLivewireComponent( $component, $path = null ) : App
    {
        $this->app->filter('livewire_components', function ( $components ) use ( $component, $path ) {
            if ( is_array($component) )
                $components = array_merge($components, $component);
            else
                $components[ $component ] = $path;

            return $components;
        });

        return $this;
    }

    /**
     * @param       $name
     * @param array $items
     *
     * @return AutoloadManager
     */
    public function autoload( $name, $items = [] ) : AutoloadManager
    {
        if ( array_key_exists($name, $this->autoload) )
            return $this->autoload[ $name ];

        $this->autoload[ $name ] = new AutoloadManager($name, $items);

        return $this->autoload[ $name ];
    }

    /**
     * @param $name
     *
     * @return CacheManager
     */
    public function cache( $name ) : CacheManager
    {
        $this->autoload[ $name ] = new CacheManager($name);

        return $this->autoload[ $name ];
    }

    public function getAutoload( $key = null )
    {
        if ( !is_null($key) && array_key_exists($key, $this->autoload) )
            return $this->autoload[ $key ];

        return $this->autoload;
    }

    public function getCache( $key = null )
    {
        if ( !is_null($key) )
            return Arr::get($this->cache, $key);

        return $this->cache;
    }
}
