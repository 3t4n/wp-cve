<?php

namespace Modular\ConnectorDependencies\Illuminate\Foundation\Console;

use Modular\ConnectorDependencies\Illuminate\Bus\Queueable;
use Modular\ConnectorDependencies\Illuminate\Contracts\Console\Kernel as KernelContract;
use Modular\ConnectorDependencies\Illuminate\Contracts\Queue\ShouldQueue;
use Modular\ConnectorDependencies\Illuminate\Foundation\Bus\Dispatchable;
/** @internal */
class QueuedCommand implements ShouldQueue
{
    use Dispatchable, Queueable;
    /**
     * The data to pass to the Artisan command.
     *
     * @var array
     */
    protected $data;
    /**
     * Create a new job instance.
     *
     * @param  array  $data
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
    /**
     * Handle the job.
     *
     * @param  \Illuminate\Contracts\Console\Kernel  $kernel
     * @return void
     */
    public function handle(KernelContract $kernel)
    {
        $kernel->call(...\array_values($this->data));
    }
}
