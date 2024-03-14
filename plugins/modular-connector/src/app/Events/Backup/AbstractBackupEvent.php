<?php

namespace Modular\Connector\Events\Backup;

use Modular\Connector\Events\AbstractEvent;

class AbstractBackupEvent extends AbstractEvent
{
    /**
     * @param string $mrid
     * @param array $payload
     */
    public function __construct(string $mrid, array $payload)
    {
        parent::__construct($mrid, $payload);
    }
}
