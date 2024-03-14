<?php

/*
|--------------------------------------------------------------------------
| Register The Composer Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so we do not have to manually load any of
| our application's PHP classes. It just feels great to relax.
|
*/

/**
 * Loads generated class maps for autoloading.
 *
 * @since 1.0.0
 * @access private
 */
function modular_connector_autoload_classes()
{
    $autoloads = [
        __DIR__ . '/../../vendor/composer/autoload_classmap.php',
        __DIR__ . '/../../vendor_prefixed/composer/autoload_classmap.php'
    ];

    $classMap = [];

    foreach ($autoloads as $autoload) {
        if (file_exists($autoload)) {
            $classMap = array_merge($classMap, include $autoload);
        }
    }

    spl_autoload_register(
        function ($class) use ($classMap) {
            if (isset($classMap[$class])) {


                require_once $classMap[$class];
            }
        },
        true,
        true
    );
}

modular_connector_autoload_classes();

/**
 * Loads files containing functions from generated file map.
 *
 * @since 1.0.0
 * @access private
 */
function modular_connector_autoload_vendor_files()
{
    $autoloads = [
        __DIR__ . '/../../vendor/composer/autoload_files.php',
        __DIR__ . '/../../vendor_prefixed/composer/autoload_files.php'
    ];

    $files = [];

    foreach ($autoloads as $autoload) {
        if (file_exists($autoload)) {
            $files = array_merge($files, include $autoload);
        }
    }

    foreach ($files as $fileIdentifier => $file) {
        require_once $file;
    }
}

modular_connector_autoload_vendor_files();

$app = require __DIR__ . '/app.php';

$kernel = $app->make(Modular\ConnectorDependencies\Illuminate\Contracts\Http\Kernel::class);

$kernel->handle(
    $request = Modular\ConnectorDependencies\Illuminate\Http\Request::capture()
);
