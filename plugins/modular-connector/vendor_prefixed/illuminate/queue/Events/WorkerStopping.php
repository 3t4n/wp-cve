<?php

namespace Modular\ConnectorDependencies\Illuminate\Queue\Events;

/** @internal */
class WorkerStopping
{
    /**
     * The exit status.
     *
     * @var int
     */
    public $status;
    /**
     * Create a new event instance.
     *
     * @param  int  $status
     * @return void
     */
    public function __construct($status = 0)
    {
        $this->status = $status;
    }
}
