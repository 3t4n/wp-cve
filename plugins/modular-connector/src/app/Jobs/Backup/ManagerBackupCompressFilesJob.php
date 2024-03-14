<?php

namespace Modular\Connector\Jobs\Backup;

use Modular\Connector\Jobs\AbstractJob;
use Modular\Connector\Services\Backup\BackupPart;
use Modular\Connector\Services\Helpers\File;

class ManagerBackupCompressFilesJob extends AbstractJob
{
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public ?string $queue = 'backups';

    /**
     * @var BackupPart
     */
    public BackupPart $part;

    /**
     * @param BackupPart $part
     */
    public function __construct(BackupPart $part)
    {
        $this->part = $part;
    }

    /**
     * @throws \Throwable
     * @throws \ErrorException
     */
    public function handle()
    {
        $part = $this->part;
        $part->markAsInProgress();

        try {
            $finder = $part->getFinder(true);

            $zip = File::openZip($part->getZipPath());

            $foundFiles = false;

            foreach ($finder as $item) {
                $foundFiles = true;

                $itemSize = $item->getSize();
                $item = File::mapItem($item);
                File::addToZip($zip, $item);

                $part->offset++;

                // We estimate that the files will be compressed to 90% of their original size.
                $part->batchSize += $itemSize * .9;

                if ($this->part->checkIfBatchSizeIsOversize($zip)) {
                    break;
                }
            }

            $this->part->checkFilesIsReady($zip, $foundFiles);
        } catch (\Throwable $e) {
            $this->part->markAsFailed(BackupPart::STATUS_FAILED_EXPORT_FILES, $e);

            throw $e;
        }
    }
}
