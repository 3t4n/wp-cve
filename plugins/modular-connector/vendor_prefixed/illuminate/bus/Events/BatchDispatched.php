<?php

namespace Modular\ConnectorDependencies\Illuminate\Bus\Events;

use Modular\ConnectorDependencies\Illuminate\Bus\Batch;
/** @internal */
class BatchDispatched
{
    /**
     * The batch instance.
     *
     * @var \Illuminate\Bus\Batch
     */
    public $batch;
    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Bus\Batch  $batch
     * @return void
     */
    public function __construct(Batch $batch)
    {
        $this->batch = $batch;
    }
}
