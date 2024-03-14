<?php

namespace Modular\Connector\Providers;

use Modular\Connector\Services\Manager;
use Modular\Connector\Services\Manager\ManagerBackup;
use Modular\Connector\Services\Manager\ManagerCore;
use Modular\Connector\Services\Manager\ManagerDatabase;
use Modular\Connector\Services\Manager\ManagerPlugin;
use Modular\Connector\Services\Manager\ManagerServer;
use Modular\Connector\Services\Manager\ManagerTheme;
use Modular\Connector\Services\Manager\ManagerTranslation;
use Modular\Connector\Services\Manager\ManagerWhiteLabel;
use Modular\ConnectorDependencies\Illuminate\Support\ServiceProvider;

class ManagerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('manager-connector-backup', function() {
            return new ManagerBackup();
        });

        $this->app->singleton('manager-connector-core', function() {
            return new ManagerCore();
        });

        $this->app->singleton('manager-connector-database', function() {
            return new ManagerDatabase();
        });

        $this->app->singleton('manager-connector', function() {
            return new Manager();
        });

        $this->app->singleton('manager-connector-plugin', function() {
            return new ManagerPlugin();
        });

        $this->app->singleton('manager-connector-server', function() {
            return new ManagerServer();
        });

        $this->app->singleton('manager-connector-theme', function() {
            return new ManagerTheme();
        });

        $this->app->singleton('manager-connector-translation', function() {
            return new ManagerTranslation();
        });

        $this->app->singleton('manager-white-label', function() {
            return new ManagerWhiteLabel();
        });

        $this->app->terminating(function () {
            \Modular\Connector\Queue\Dispatcher::forceDestroy();
        });
    }
}
