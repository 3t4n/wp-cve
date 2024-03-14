<?php

namespace Modular\ConnectorDependencies\Illuminate\Events;

use Modular\ConnectorDependencies\Illuminate\Contracts\Queue\Factory as QueueFactoryContract;
use Modular\ConnectorDependencies\Illuminate\Support\ServiceProvider;
/** @internal */
class EventServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('events', function ($app) {
            return (new Dispatcher($app))->setQueueResolver(function () use($app) {
                return $app->make(QueueFactoryContract::class);
            });
        });
    }
}
