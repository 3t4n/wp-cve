<?php

namespace Modular\ConnectorDependencies\Illuminate\Console\Events;

use Modular\ConnectorDependencies\Illuminate\Console\Scheduling\Event;
/** @internal */
class ScheduledTaskFinished
{
    /**
     * The scheduled event that ran.
     *
     * @var \Illuminate\Console\Scheduling\Event
     */
    public $task;
    /**
     * The runtime of the scheduled event.
     *
     * @var float
     */
    public $runtime;
    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Console\Scheduling\Event  $task
     * @param  float  $runtime
     * @return void
     */
    public function __construct(Event $task, $runtime)
    {
        $this->task = $task;
        $this->runtime = $runtime;
    }
}
