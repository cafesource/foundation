<?php

return [

    'providers' => [
        'foundation' => Cafesource\Foundation\RouteServiceProvider::class,
        'option'     => Cafesource\Option\OptionServiceProvider::class,
        'location'   => Cafesource\Location\LocationServiceProvider::class,
        'gateway'    => Cafesource\Gateway\GatewayServiceProvider::class,
        'user'       => Cafesource\User\UserServiceProvider::class,
        'admin'      => Cafesource\Admin\AdminServiceProvider::class,
        // Cafesource\Menu\MenuServiceProvider::class,
        // Cafesource\Post\PostServiceProvider::class,
    ],

    'routes' => [
        'admin' => [
            'prefix'     => 'admin',
            'name'       => 'admin.',
            'middleware' => ['web', 'auth:sanctum'],
            'namespace'  => null,
            'path'       => base_path('cafesource/admin/routes/web.php')
        ]
    ],

    'logging'  => [
        'model' => \Cafesource\Foundation\Models\Log::class,
        /**
         * Default: 30day
         */
        'daily' => 30
    ],
    'autoload' => [
        'status' => true,
        'driver' => \Cafesource\Foundation\Autoload\LoadManager::class
    ]
];
