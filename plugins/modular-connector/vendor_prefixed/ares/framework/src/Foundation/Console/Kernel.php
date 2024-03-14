<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Foundation\Console;

use Modular\ConnectorDependencies\Illuminate\Foundation\Console\Kernel as FoundationKernel;
/** @internal */
class Kernel extends FoundationKernel
{
    /**
     * The bootstrap classes for the application.
     *
     * @var string[]
     */
    protected $bootstrappers = [\Modular\ConnectorDependencies\Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class, \Modular\ConnectorDependencies\Illuminate\Foundation\Bootstrap\LoadConfiguration::class, \Modular\ConnectorDependencies\Ares\Framework\Foundation\Bootstrap\HandleExceptions::class, \Modular\ConnectorDependencies\Illuminate\Foundation\Bootstrap\RegisterFacades::class, \Modular\ConnectorDependencies\Illuminate\Foundation\Bootstrap\RegisterProviders::class, \Modular\ConnectorDependencies\Illuminate\Foundation\Bootstrap\BootProviders::class];
}
