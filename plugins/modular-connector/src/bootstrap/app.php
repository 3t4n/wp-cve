<?php

use Modular\ConnectorDependencies\Ares\Framework\Foundation\Application;

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/
$app = new Application(dirname(__DIR__));

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/
$app->singleton(
    Modular\ConnectorDependencies\Illuminate\Contracts\Http\Kernel::class,
    Modular\Connector\Http\Kernel::class
);

$app->singleton(
    Modular\ConnectorDependencies\Illuminate\Contracts\Console\Kernel::class,
    Modular\Connector\Console\Kernel::class
);

$app->singleton(
    Modular\ConnectorDependencies\Illuminate\Contracts\Debug\ExceptionHandler::class,
    Modular\Connector\Exceptions\Handler::class
);

return $app;
