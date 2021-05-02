<?php

namespace Cafesource\Foundation\Autoload;

use Illuminate\Support\Arr;

class LoadManager
{
    protected string $name;
    protected array  $items   = [];
    protected array  $default = [];

    public function __construct( $name, $items = [] )
    {
        $this->name    = $name;
        $this->items   = $items;
        $this->default = $items;
    }

    public function items()
    {
        return $this->items;
    }

    public function has( $key )
    {
        return array_key_exists($key, $this->items);
    }

    public function set( $key, $value )
    {
        $this->items[ $key ] = $value;

        return $this;
    }

    public function add( $key, $value, $default = null )
    {
        $this->items[ $key ] = $value;

        if ( !is_null($default) )
            $this->default[ $key ] = $default;

        return $this;
    }

    public function insert( $data )
    {
        $this->items = array_merge($this->items, $data);

        return $this;
    }

    /**
     * @param      $key
     * @param null $default
     *
     * @return mixed
     */
    public function get( $key, $default = null )
    {
        if ( $this->has($key) )
            return Arr::get($this->items, $key);

        return $default;
    }

    public function default( $key )
    {
        return $this->default[ $key ] ?? null;
    }

    public function filter( $key, callable $callable ) : LoadManager
    {
        $value = $this->get($key);
        $value = call_user_func_array($callable, [$value, $this->default($key)]);

        $this->set($key, $value);

        return $this;
    }

    public function map( callable $callable )
    {
        return array_map($callable, $this->items, $this->default);
    }

}
