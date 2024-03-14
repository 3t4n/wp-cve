<?php

namespace Modular\ConnectorDependencies\Illuminate\Contracts\Queue;

use InvalidArgumentException;
/** @internal */
class EntityNotFoundException extends InvalidArgumentException
{
    /**
     * Create a new exception instance.
     *
     * @param  string  $type
     * @param  mixed  $id
     * @return void
     */
    public function __construct($type, $id)
    {
        $id = (string) $id;
        parent::__construct("Queueable entity [{$type}] not found for ID [{$id}].");
    }
}
