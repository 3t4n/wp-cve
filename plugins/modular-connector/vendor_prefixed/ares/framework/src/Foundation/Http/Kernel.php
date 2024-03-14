<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Foundation\Http;

use Modular\ConnectorDependencies\Illuminate\Contracts\Foundation\Application;
use Modular\ConnectorDependencies\Illuminate\Foundation\Http\Kernel as FoundationKernel;
use Modular\ConnectorDependencies\Illuminate\Support\Facades\Facade;
/** @internal */
class Kernel extends FoundationKernel
{
    /**
     * The bootstrap classes for the application.
     *
     * @var string[]
     */
    protected $bootstrappers = [\Modular\ConnectorDependencies\Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class, \Modular\ConnectorDependencies\Illuminate\Foundation\Bootstrap\LoadConfiguration::class, \Modular\ConnectorDependencies\Ares\Framework\Foundation\Bootstrap\HandleExceptions::class, \Modular\ConnectorDependencies\Illuminate\Foundation\Bootstrap\RegisterFacades::class, \Modular\ConnectorDependencies\Illuminate\Foundation\Bootstrap\RegisterProviders::class, \Modular\ConnectorDependencies\Illuminate\Foundation\Bootstrap\BootProviders::class];
    /**
     * The priority-sorted list of middleware.
     *
     * Forces non-global middleware to always be in the given order.
     *
     * @var string[]
     */
    protected $middlewarePriority = [];
    /**
     * Create a new HTTP kernel instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param \Illuminate\Routing\Router $router
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }
    /**
     * Send the given request through the middleware / router.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function sendRequestThroughRouter($request)
    {
        $this->app->instance('request', $request);
        Facade::clearResolvedInstance('request');
        $this->bootstrap();
        return $this;
    }
}
