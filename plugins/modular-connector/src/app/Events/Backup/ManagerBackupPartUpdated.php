<?php

namespace Modular\Connector\Events\Backup;

use Modular\Connector\Services\Backup\BackupPart;

class ManagerBackupPartUpdated extends AbstractBackupEvent
{
    /**
     * @param BackupPart $part
     * @param array $payload
     */
    public function __construct(BackupPart $part, array $payload = [])
    {
        parent::__construct($part->options->mrid, $part->toArray() + $payload);
    }
}
