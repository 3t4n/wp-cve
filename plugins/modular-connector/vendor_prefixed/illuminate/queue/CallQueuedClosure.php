<?php

namespace Modular\ConnectorDependencies\Illuminate\Queue;

use Closure;
use Modular\ConnectorDependencies\Illuminate\Bus\Batchable;
use Modular\ConnectorDependencies\Illuminate\Bus\Queueable;
use Modular\ConnectorDependencies\Illuminate\Contracts\Container\Container;
use Modular\ConnectorDependencies\Illuminate\Contracts\Queue\ShouldQueue;
use Modular\ConnectorDependencies\Illuminate\Foundation\Bus\Dispatchable;
use ReflectionFunction;
/** @internal */
class CallQueuedClosure implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * The serializable Closure instance.
     *
     * @var \Laravel\SerializableClosure\SerializableClosure
     */
    public $closure;
    /**
     * The callbacks that should be executed on failure.
     *
     * @var array
     */
    public $failureCallbacks = [];
    /**
     * Indicate if the job should be deleted when models are missing.
     *
     * @var bool
     */
    public $deleteWhenMissingModels = \true;
    /**
     * Create a new job instance.
     *
     * @param  \Laravel\SerializableClosure\SerializableClosure  $closure
     * @return void
     */
    public function __construct($closure)
    {
        $this->closure = $closure;
    }
    /**
     * Create a new job instance.
     *
     * @param  \Closure  $job
     * @return self
     */
    public static function create(Closure $job)
    {
        return new self(SerializableClosureFactory::make($job));
    }
    /**
     * Execute the job.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return void
     */
    public function handle(Container $container)
    {
        $container->call($this->closure->getClosure(), ['job' => $this]);
    }
    /**
     * Add a callback to be executed if the job fails.
     *
     * @param  callable  $callback
     * @return $this
     */
    public function onFailure($callback)
    {
        $this->failureCallbacks[] = $callback instanceof Closure ? SerializableClosureFactory::make($callback) : $callback;
        return $this;
    }
    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $e
     * @return void
     */
    public function failed($e)
    {
        foreach ($this->failureCallbacks as $callback) {
            $callback($e);
        }
    }
    /**
     * Get the display name for the queued job.
     *
     * @return string
     */
    public function displayName()
    {
        $reflection = new ReflectionFunction($this->closure->getClosure());
        return 'Closure (' . \basename($reflection->getFileName()) . ':' . $reflection->getStartLine() . ')';
    }
}
