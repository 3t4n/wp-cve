<?php

namespace Modular\ConnectorDependencies\Illuminate\Queue;

use InvalidArgumentException;
/** @internal */
class InvalidPayloadException extends InvalidArgumentException
{
    /**
     * Create a new exception instance.
     *
     * @param  string|null  $message
     * @return void
     */
    public function __construct($message = null)
    {
        parent::__construct($message ?: \json_last_error());
    }
}
