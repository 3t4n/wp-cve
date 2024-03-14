<?php

namespace Modular\Connector\Providers;

use Modular\Connector\Events\Backup\ManagerBackupFailedCreation;
use Modular\Connector\Events\Backup\ManagerBackupPartsCalculated;
use Modular\Connector\Events\Backup\ManagerBackupPartUpdated;
use Modular\Connector\Events\ManagerHealthUpdated;
use Modular\Connector\Events\ManagerItemsUpdated;
use Modular\Connector\Events\ManagerItemsUpgraded;
use Modular\Connector\Listeners\BackupRemoveEventListener;
use Modular\Connector\Listeners\HookEventListener;
use Modular\ConnectorDependencies\Illuminate\Support\Facades\Event;
use Modular\ConnectorDependencies\Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected array $listen = [
        ManagerItemsUpdated::class => [
            HookEventListener::class
        ],
        ManagerItemsUpgraded::class => [
            HookEventListener::class
        ],
        ManagerHealthUpdated::class => [
            HookEventListener::class
        ],
        ManagerBackupPartsCalculated::class => [
            HookEventListener::class
        ],
        ManagerBackupPartUpdated::class => [
            BackupRemoveEventListener::class,
            HookEventListener::class,
        ],
        ManagerBackupFailedCreation::class => [
            BackupRemoveEventListener::class,
            HookEventListener::class
        ],
    ];

    /**
     * Register the application's event listeners.
     *
     * @return void
     */
    public function register()
    {
        $this->booting(function () {
            $events = $this->getEvents();

            foreach ($events as $event => $listeners) {
                foreach (array_unique($listeners, SORT_REGULAR) as $listener) {
                    Event::listen($event, $listener);
                }
            }
        });
    }

    /**
     * Get the events and handlers.
     *
     * @return array
     */
    public function listens()
    {
        return $this->listen;
    }

    /**
     * Get the discovered events and listeners for the application.
     *
     * @return array
     */
    public function getEvents()
    {
        if ($this->app->eventsAreCached()) {
            $cache = require $this->app->getCachedEventsPath();

            return $cache[get_class($this)] ?? [];
        } else {
            return $this->listens();
        }
    }
}
