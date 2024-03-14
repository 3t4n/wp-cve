<?php

namespace Modular\ConnectorDependencies\Illuminate\Console\Events;

use Modular\ConnectorDependencies\Illuminate\Console\Scheduling\Event;
/** @internal */
class ScheduledTaskStarting
{
    /**
     * The scheduled event being run.
     *
     * @var \Illuminate\Console\Scheduling\Event
     */
    public $task;
    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Console\Scheduling\Event  $task
     * @return void
     */
    public function __construct(Event $task)
    {
        $this->task = $task;
    }
}
