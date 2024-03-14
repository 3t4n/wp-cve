<?php

namespace Modular\Connector\Jobs;

use Modular\Connector\Queue\Dispatcher;

class PendingDispatch
{
    /**
     * The job.
     *
     * @var mixed
     */
    protected $job;

    /**
     * Create a new pending job dispatch.
     *
     * @param mixed $job
     * @return void
     */
    public function __construct($job)
    {
        $this->job = $job;
    }

    /**
     * Send the job to the dispatcher.
     */
    public function __destruct()
    {
        Dispatcher::getInstance()->dispatchToQueue($this->job, $this->job->queue);
    }
}
