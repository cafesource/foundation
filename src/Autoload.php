<?php

namespace Cafesource\Foundation;

use Illuminate\Support\Arr;

class Autoload
{
    /**
     * @var $name
     */
    protected $name;

    /**
     * @var array|mixed
     */
    protected $items = [];

    /**
     * @var Filter
     */
    private Filter $filter;

    /**
     * Autoload constructor.
     *
     * @param       $name
     * @param array $items
     */
    public function __construct( $name, $items = [] )
    {
        $this->name    = $name;
        $this->items   = $items;
        $this->default = $items;

        $this->filter = new Filter();
    }

    /**
     * @return array|mixed
     */
    public function items()
    {
        return $this->items;
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function has( $key )
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function set( $key, $value )
    {
        $this->items[ $key ] = $value;

        return $this;
    }

    /**
     * @param      $key
     * @param      $value
     * @param null $default
     *
     * @return $this
     */
    public function add( $key, $value, $default = null )
    {
        $this->items[ $key ] = $value;

        if ( !is_null($default) )
            $this->default[ $key ] = $default;

        return $this;
    }

    /**
     * @param $data
     *
     * @return $this
     */
    public function push( $data )
    {
        $this->items = array_merge($this->items, $data);

        return $this;
    }

    /**
     * @param      $key
     * @param null $default
     *
     * @return mixed|null
     */
    public function get( $key, $default = null )
    {
        if ( $this->has($key) ) {
            $value = Arr::get($this->items, $key);

            return $this->filter->apply($key, $value);
        }

        return $default;
    }

    /**
     * @param $key
     *
     * @return mixed|null
     */
    public function default( $key )
    {
        return $this->default[ $key ] ?? null;
    }

    /**
     * @param $key
     * @param $callback
     *
     * @return $this
     */
    public function filter( $key, $callback ) : Autoload
    {
        $value = $this->get($key);

        if ( is_callable($callback) ) {
            $value = call_user_func_array($callback, [$value, $this->default($key)]);
        } else
            $value = $callback;

        $this->set($key, $value);

        return $this;
    }

    /**
     * @param          $key
     * @param callable $callable
     * @param int      $arguments
     * @param int      $priority
     *
     * @return $this
     */
    public function addFilter( $key, callable $callable, $arguments = 1, $priority = 10 ) : Autoload
    {
        $this->filter->add($key, $callable, $arguments, $priority);

        return $this;
    }

}
