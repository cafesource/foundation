<?php


namespace Cafesource\Foundation\Facades;


use Illuminate\Support\Facades\Facade;
use Cafesource\Foundation\Autoload\LoadManager;
use Cafesource\Foundation\App as CafesourceManager;

/**
 * Class Cafesource
 *
 * @method static array configs()
 * @method static array serviceProviders()
 * @method static array routes()
 * @method static CafesourceManager addRoute(string $name, $path, array $options = [])
 * @method static array livewireComponents()
 * @method static CafesourceManager addLivewireComponent()
 * @method static LoadManager autoload(string $name, array $data = [])
 * @method static LoadManager getAutoload(string $key = null)
 * @method static mixed cache(string $name)
 * @method static array getCache()
 *
 * @package Cafesource\Foundation\Facades
 */
class Cafesource extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cafesource.foundation';
    }
}
