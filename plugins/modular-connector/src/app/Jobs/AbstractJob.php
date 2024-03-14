<?php

namespace Modular\Connector\Jobs;

use Modular\Connector\Queue\Dispatcher;
use Modular\ConnectorDependencies\Illuminate\Queue\SerializesModels;
use Modular\ConnectorDependencies\Illuminate\Support\Facades\Config;

abstract class AbstractJob
{
    use SerializesModels;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public ?string $queue = null;

    /**
     * Dispatch the job with the given arguments.
     *
     * @param mixed ...$arguments
     * @return mixed
     */
    public static function dispatch(...$arguments)
    {
        if (Config::get('app.queue.sync') === true) {
            return static::dispatchSync(...$arguments);
        }

        return new PendingDispatch(new static(...$arguments));
    }

    /**
     * Set the desired queue for the job.
     *
     * @param string|null $queue
     * @return $this
     */
    public function onQueue(?string $queue)
    {
        $this->queue = $queue;

        return $this;
    }

    /**
     * Dispatch a command to its appropriate handler in the current process.
     *
     * Queueable jobs will be dispatched to the "sync" queue.
     *
     * @param mixed ...$arguments
     * @return mixed
     */
    public static function dispatchSync(...$arguments)
    {
        return Dispatcher::getInstance()->dispatchSync(new static(...$arguments));
    }

    abstract public function handle();
}
