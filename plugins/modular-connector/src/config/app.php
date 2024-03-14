<?php

return [
    'debug' => defined('MODULAR_CONNECTOR_DEBUG') && MODULAR_CONNECTOR_DEBUG,
    'env' => defined('MODULAR_CONNECTOR_ENV') ? MODULAR_CONNECTOR_ENV : 'production',

    'controllers' => [
        [
            'namespace' => 'Modular\\Connector\\Http\\Controllers\\',
            'path' => \Modular\ConnectorDependencies\base_path('app/Http/Controllers'),
        ]
    ],

    'console' => [
        'namespace' => 'Modular\\Connector\\Console\\Commands',
        'path' => \Modular\ConnectorDependencies\base_path('app/Console/commands'),
    ],

    'storage' => [
        'path' => \Modular\ConnectorDependencies\base_path('storage/app/store'),
    ],

    'cache' => [
        'path' => \Modular\ConnectorDependencies\base_path('bootstrap/cache'),
    ],

    'timezone' => defined('MODULAR_CONNECTOR_TIMEZONE') ? MODULAR_CONNECTOR_TIMEZONE : 'UTC',

    'queue' => [
        'sync' => defined('MODULAR_CONNECTOR_QUEUE_SYNC') ? MODULAR_CONNECTOR_QUEUE_SYNC : false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */
    'providers' => [
        Modular\ConnectorDependencies\Illuminate\Filesystem\FilesystemServiceProvider::class,
        Modular\ConnectorDependencies\Illuminate\Database\DatabaseServiceProvider::class,
        Modular\ConnectorDependencies\Illuminate\Validation\ValidationServiceProvider::class,
        Modular\ConnectorDependencies\Ares\Framework\Setup\RegisterWordpressAsPluginProvider::class,
        Modular\Connector\Providers\ManagerServiceProvider::class,
        Modular\Connector\Providers\EventServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */
    'aliases' => [
        'Artisan' => Modular\ConnectorDependencies\Illuminate\Support\Facades\Artisan::class,
        'Storage' => Modular\ConnectorDependencies\Illuminate\Support\Facades\Storage::class,
        'Validator' => Modular\ConnectorDependencies\Illuminate\Support\Facades\Validator::class,
        'View' => Modular\ConnectorDependencies\Illuminate\Support\Facades\View::class,
    ],
];
