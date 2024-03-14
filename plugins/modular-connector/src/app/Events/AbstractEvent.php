<?php

namespace Modular\Connector\Events;

use Modular\ConnectorDependencies\Illuminate\Queue\SerializesModels;
use function Modular\ConnectorDependencies\event;

abstract class AbstractEvent
{
    use SerializesModels;

    /**
     * Request ID from Modular
     *
     * @var string
     */
    public string $mrid;

    /**
     * @var
     */
    public $payload;

    /***
     * @param string $mrid
     * @param $payload
     */
    public function __construct(string $mrid, $payload)
    {
        $this->mrid = $mrid;
        $this->payload = $payload;
    }

    /**
     * Dispatch the event with the given arguments.
     *
     * @return void
     */
    public static function dispatch()
    {
        return event(new static(...func_get_args()));
    }
}
