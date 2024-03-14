<?php

namespace Modular\Connector\Http;

use Modular\ConnectorDependencies\Ares\Framework\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The bootstrap classes for the application.
     *
     * @var string[]
     */
    protected $bootstrappers = [
        \Modular\ConnectorDependencies\Illuminate\Foundation\Bootstrap\LoadConfiguration::class,
        \Modular\Connector\Exceptions\HandleExceptions::class,
        \Modular\ConnectorDependencies\Illuminate\Foundation\Bootstrap\RegisterFacades::class,
        \Modular\ConnectorDependencies\Illuminate\Foundation\Bootstrap\RegisterProviders::class,
        \Modular\ConnectorDependencies\Illuminate\Foundation\Bootstrap\BootProviders::class
    ];
}
