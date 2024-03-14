<?php

namespace Modular\ConnectorDependencies\Illuminate\Database;

use Modular\ConnectorDependencies\Faker\Factory as FakerFactory;
use Modular\ConnectorDependencies\Faker\Generator as FakerGenerator;
use Modular\ConnectorDependencies\Illuminate\Contracts\Queue\EntityResolver;
use Modular\ConnectorDependencies\Illuminate\Database\Connectors\ConnectionFactory;
use Modular\ConnectorDependencies\Illuminate\Database\Eloquent\Model;
use Modular\ConnectorDependencies\Illuminate\Database\Eloquent\QueueEntityResolver;
use Modular\ConnectorDependencies\Illuminate\Support\ServiceProvider;
/** @internal */
class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * The array of resolved Faker instances.
     *
     * @var array
     */
    protected static $fakers = [];
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        Model::setConnectionResolver($this->app['db']);
        Model::setEventDispatcher($this->app['events']);
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Model::clearBootedModels();
        $this->registerConnectionServices();
        $this->registerEloquentFactory();
        $this->registerQueueableEntityResolver();
    }
    /**
     * Register the primary database bindings.
     *
     * @return void
     */
    protected function registerConnectionServices()
    {
        // The connection factory is used to create the actual connection instances on
        // the database. We will inject the factory into the manager so that it may
        // make the connections while they are actually needed and not of before.
        $this->app->singleton('db.factory', function ($app) {
            return new ConnectionFactory($app);
        });
        // The database manager is used to resolve various connections, since multiple
        // connections might be managed. It also implements the connection resolver
        // interface which may be used by other components requiring connections.
        $this->app->singleton('db', function ($app) {
            return new DatabaseManager($app, $app['db.factory']);
        });
        $this->app->bind('db.connection', function ($app) {
            return $app['db']->connection();
        });
        $this->app->singleton('db.transactions', function ($app) {
            return new DatabaseTransactionsManager();
        });
    }
    /**
     * Register the Eloquent factory instance in the container.
     *
     * @return void
     */
    protected function registerEloquentFactory()
    {
        $this->app->singleton(FakerGenerator::class, function ($app, $parameters) {
            $locale = $parameters['locale'] ?? $app['config']->get('app.faker_locale', 'en_US');
            if (!isset(static::$fakers[$locale])) {
                static::$fakers[$locale] = FakerFactory::create($locale);
            }
            static::$fakers[$locale]->unique(\true);
            return static::$fakers[$locale];
        });
    }
    /**
     * Register the queueable entity resolver implementation.
     *
     * @return void
     */
    protected function registerQueueableEntityResolver()
    {
        $this->app->singleton(EntityResolver::class, function () {
            return new QueueEntityResolver();
        });
    }
}
