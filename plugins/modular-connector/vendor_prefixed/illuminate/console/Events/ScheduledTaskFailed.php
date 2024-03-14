<?php

namespace Modular\ConnectorDependencies\Illuminate\Console\Events;

use Modular\ConnectorDependencies\Illuminate\Console\Scheduling\Event;
use Throwable;
/** @internal */
class ScheduledTaskFailed
{
    /**
     * The scheduled event that failed.
     *
     * @var \Illuminate\Console\Scheduling\Event
     */
    public $task;
    /**
     * The exception that was thrown.
     *
     * @var \Throwable
     */
    public $exception;
    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Console\Scheduling\Event  $task
     * @param  \Throwable  $exception
     * @return void
     */
    public function __construct(Event $task, Throwable $exception)
    {
        $this->task = $task;
        $this->exception = $exception;
    }
}
