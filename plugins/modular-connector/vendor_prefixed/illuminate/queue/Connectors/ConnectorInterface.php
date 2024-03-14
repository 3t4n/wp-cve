<?php

namespace Modular\ConnectorDependencies\Illuminate\Queue\Connectors;

/** @internal */
interface ConnectorInterface
{
    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config);
}
