<?php

namespace Modular\ConnectorDependencies\Illuminate\Pipeline;

use Modular\ConnectorDependencies\Illuminate\Contracts\Pipeline\Hub as PipelineHubContract;
use Modular\ConnectorDependencies\Illuminate\Contracts\Support\DeferrableProvider;
use Modular\ConnectorDependencies\Illuminate\Support\ServiceProvider;
/** @internal */
class PipelineServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PipelineHubContract::class, Hub::class);
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [PipelineHubContract::class];
    }
}
