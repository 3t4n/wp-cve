<?php

namespace Modular\Connector\Events\Backup;

use Modular\Connector\Services\Backup\BackupOptions;
use Modular\Connector\Services\Backup\BackupPart;
use Modular\ConnectorDependencies\Illuminate\Support\Collection;

class ManagerBackupPartsCalculated extends AbstractBackupEvent
{
    /**
     * @param Collection<BackupPart> $parts
     */
    public function __construct(BackupOptions $part, Collection $parts)
    {
        parent::__construct($part->mrid, [
            'parts' => $parts->map(function (BackupPart $part) {
                return $part->toArray();
            })
                ->toArray()
        ]);
    }
}
