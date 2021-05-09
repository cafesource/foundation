<?php

namespace Cafesource\Foundation;

class Filter
{
    protected $listeners = [];

    public function __construct()
    {
        $this->listeners = collect([]);
    }

    /**
     * @param string   $name
     * @param callable $callback
     * @param int      $arguments
     * @param int      $priority
     *
     * @return $this
     */
    public function add( string $name, callable $callback, int $arguments = 1, int $priority = 20 )
    {
        $this->listeners->push([
            'name'      => $name,
            'callback'  => $callback,
            'priority'  => $priority,
            'arguments' => $arguments
        ]);

        return $this;
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function has( $name )
    {
        $count = $this->listeners->where('name', $name)->count();
        return $count > 0 ? true : false;
    }

    /**
     * @param $name
     *
     * @return \Illuminate\Support\Collection
     */
    public function listeners( $name )
    {
        return $this->listeners->where('name', $name)->sortBy('priority');
    }

    /**
     * @param $name
     * @param $value
     *
     * @return mixed
     */
    public function apply( $name, $value )
    {
        $this->lastValue = $value;
        if ( $this->has($name) ) {
            $this->listeners($name)->each(function ( $listener ) use ( $name, $value ) {
                $parameters = [];
                $args[ 0 ]  = $this->lastValue;
                for ( $i = 0; $i < $listener[ 'arguments' ]; $i++ ) {
                    $value        = $args[ $i ] ?? null;
                    $parameters[] = $value;
                }

                $this->lastValue = call_user_func_array($listener[ 'callback' ], $parameters);
            });
        }

        return $this->lastValue;
    }

    /**
     * @param     $name
     * @param     $callback
     * @param int $priority
     */
    public function remove( $name, $callback, $priority = 20 )
    {
        if ( $this->listeners ) {
            $this->listeners->where('name', $name)
                ->filter(function ( $listener ) use ( $callback ) {
                    return $callback === $listener[ 'callback' ];
                })
                ->where('priority', $priority)
                ->each(function ( $listener, $key ) {
                    $this->listeners->forget($key);
                });
        }
    }

    /**
     * @param null $name
     */
    public function removeAll( $name = null )
    {
        if ( $name ) {
            if ( $this->listeners ) {
                $this->listeners->where('name', $name)->each(function ( $listener, $key ) {
                    $this->listeners->forget($key);
                });
            }
        } else {
            $this->listeners = collect([]);
        }
    }
}