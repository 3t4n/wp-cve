<?php

namespace Modular\Connector\Listeners;

use Modular\Connector\Jobs\Hooks\HookSendEventJob;
use Modular\ConnectorDependencies\Illuminate\Contracts\Queue\ShouldQueue;

class HookEventListener
{
    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public static function handle($event)
    {
        if ($event instanceof ShouldQueue) {
            HookSendEventJob::dispatch($event);
        } else {
            HookSendEventJob::dispatchSync($event);
        }
    }
}
