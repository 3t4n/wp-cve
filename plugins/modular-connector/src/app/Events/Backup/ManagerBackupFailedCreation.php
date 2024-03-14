<?php

namespace Modular\Connector\Events\Backup;

use Modular\Connector\Services\Backup\BackupOptions;
use Modular\Connector\Services\Manager\ManagerBackup;

class ManagerBackupFailedCreation extends AbstractBackupEvent
{
    /***
     * @param BackupOptions $part
     * @param \Throwable $e
     */
    public function __construct(BackupOptions $part, \Throwable $e)
    {
        parent::__construct(
            $part->mrid,
            [
                'options' => $part->toArray()
            ] + [
                'error' => [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
                'status' => ManagerBackup::STATUS_FAILED_IN_CREATION
            ]
        );
    }
}
