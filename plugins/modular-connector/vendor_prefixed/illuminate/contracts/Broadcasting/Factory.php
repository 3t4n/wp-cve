<?php

namespace Modular\ConnectorDependencies\Illuminate\Contracts\Broadcasting;

/** @internal */
interface Factory
{
    /**
     * Get a broadcaster implementation by name.
     *
     * @param  string|null  $name
     * @return \Illuminate\Contracts\Broadcasting\Broadcaster
     */
    public function connection($name = null);
}
