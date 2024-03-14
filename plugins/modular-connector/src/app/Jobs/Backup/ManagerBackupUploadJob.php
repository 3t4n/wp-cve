<?php

namespace Modular\Connector\Jobs\Backup;

use Modular\Connector\Facades\Backup;
use Modular\Connector\Jobs\AbstractJob;
use Modular\Connector\Services\Backup\BackupPart;
use Modular\ConnectorDependencies\GuzzleHttp\Client;
use Modular\ConnectorDependencies\GuzzleHttp\Psr7\Utils;
use Modular\ConnectorDependencies\Illuminate\Support\Facades\Storage;

class ManagerBackupUploadJob extends AbstractJob
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
    protected BackupPart $part;

    /**
     * @param BackupPart $part
     */
    public function __construct(BackupPart $part)
    {
        $this->part = $part;
    }

    public function handle()
    {
        $this->part->markAsUploading();

        $name = $this->part->getZipName();
        $relativePath = Backup::path($name . '.zip');

        if (!Storage::exists($relativePath)) {
            $this->part->markAsFailed(BackupPart::STATUS_FAILED_FILE_NOT_FOUND);

            return;
        }

        $realPath = Storage::path($relativePath);

        try {
            $uploadUri = $this->part->getUploadUri();

            $resource = fopen($realPath, 'r');
            $stream = Utils::streamFor($resource);

            $guzzle = new Client();
            $guzzle->request('PUT', $uploadUri, ['body' => $stream]);

            $this->part->markAsDone();
        } catch (\Exception $e) {
            $this->part->markAsFailed(BackupPart::STATUS_FAILED_UPLOADED, $e);

            throw $e;
        }
    }
}
