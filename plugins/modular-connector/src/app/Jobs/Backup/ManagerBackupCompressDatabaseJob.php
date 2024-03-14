<?php

namespace Modular\Connector\Jobs\Backup;

use Modular\Connector\Facades\Backup;
use Modular\Connector\Facades\Database;
use Modular\Connector\Jobs\AbstractJob;
use Modular\Connector\Services\Backup\BackupPart;
use Modular\Connector\Services\Helpers\File;
use Modular\ConnectorDependencies\Illuminate\Support\Facades\Storage;

class ManagerBackupCompressDatabaseJob extends AbstractJob
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
            $path = Backup::path($this->part->options->name . '.sql');
            $storagePath = Storage::path($path);

            Database::dump($storagePath, $this->part->options);

            $zip = File::openZip($this->part->getZipPath());

            File::addToZip($zip, [
                'type' => 'file',
                'realpath' => $storagePath,
                'path' => 'database.sql'
            ]);

            // Close the zip file after added the files
            File::closeZip($zip);

            Storage::delete($path);

            $this->part->markAsUploadPending();
        } catch (\Throwable $e) {
            $this->part->markAsFailed(BackupPart::STATUS_FAILED_EXPORT_DATABASE, $e);

            throw $e;
        }
    }
}
