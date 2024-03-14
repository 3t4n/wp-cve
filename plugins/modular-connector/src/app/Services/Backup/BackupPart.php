<?php

namespace Modular\Connector\Services\Backup;

use Modular\Connector\Events\Backup\ManagerBackupPartUpdated;
use Modular\Connector\Facades\Backup;
use Modular\Connector\Helper\OauthClient;
use Modular\Connector\Jobs\Backup\ManagerBackupCompressFilesJob;
use Modular\Connector\Jobs\Backup\ManagerBackupUploadJob;
use Modular\Connector\Services\Helpers\File;
use Modular\ConnectorDependencies\Illuminate\Support\Facades\Storage;

class BackupPart implements \JsonSerializable
{
    /**
     * @var string
     */
    public string $type;

    /**
     * @var BackupOptions
     */
    public BackupOptions $options;

    /**
     * Cursor position (offset) of finder
     *
     * @var int
     */
    public int $offset = 0;

    /**
     * @var int Total items to include in this part
     */
    public int $totalItems = 0;

    /**
     * @var int Current batch number (ex. plugins-{$batch})
     */
    public int $batch = 1;

    /**
     * @var int Current batch size
     */
    public int $batchSize = 0;

    /**
     * @var string
     */
    public string $dbKey;

    /**
     * @var string Current status of this part
     */
    public string $status;
    public const PART_TYPE_DATABASE = 'database';
    public const PART_TYPE_CORE = 'core';
    public const PART_TYPE_PLUGINS = 'plugins';
    public const PART_TYPE_THEMES = 'themes';
    public const PART_TYPE_UPLOADS = 'uploads';

    public const PART_STATUS_EXCLUDED = 'excluded';

    public const PART_STATUS_PENDING = 'pending';
    public const PART_STATUS_IN_PROGRESS = 'in_progress';
    public const PART_STATUS_UPLOAD_PENDING = 'upload_pending';
    public const PART_STATUS_UPLOADING = 'uploading';
    public const PART_STATUS_DONE = 'done';

    public const STATUS_FAILED_FILE_NOT_FOUND = 'failed_file_not_found';
    public const STATUS_FAILED_EXPORT_DATABASE = 'failed_export_database';
    public const STATUS_FAILED_UPLOADED = 'failed_uploaded';
    public const STATUS_FAILED_EXPORT_FILES = 'failed_export_files';


    public function __construct(string $type, BackupOptions $options)
    {
        $this->type = $type;
        $this->setOptions($options);
    }

    /**
     * @param BackupOptions $options
     * @return void
     */
    public function setOptions(BackupOptions $options)
    {
        $this->options = $options;
    }

    /**
     * @param string $key
     * @return void
     */
    public function setDbKey(string $key)
    {
        $this->dbKey = $key;
    }

    /**
     * @param array $extraArgs
     * @return void
     */
    protected function update(array $extraArgs = [])
    {
        BackupWorker::getInstance()->update($this);
        ManagerBackupPartUpdated::dispatch($this, $extraArgs);
    }

    /**
     * @param string $status
     * @param array $extraArgs
     * @return void
     */
    protected function markAs(string $status, array $extraArgs = [])
    {
        if ($this->status !== $status) {
            $this->status = $status;

            $this->update($extraArgs);
        }
    }

    /**
     * @return void
     */
    public function markAsPending()
    {
        $this->status = self::PART_STATUS_PENDING;
    }

    /**
     * @return void
     */
    public function markAsExcluded()
    {
        $this->status = self::PART_STATUS_EXCLUDED;
    }

    /**
     * @return void
     */
    public function markAsInProgress()
    {
        $this->markAs(self::PART_STATUS_IN_PROGRESS);
    }

    /**
     * @return void
     */
    public function markAsUploadPending()
    {
        $this->markAs(self::PART_STATUS_UPLOAD_PENDING);

        ManagerBackupUploadJob::dispatch($this);
    }

    /**
     * @return void
     */
    public function markAsUploading()
    {
        $this->markAs(self::PART_STATUS_UPLOADING);
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function markAsDone()
    {
        $this->markAs(self::PART_STATUS_DONE);

        BackupWorker::getInstance()->dispatch();
    }

    /**
     * @param string $status
     * @return void
     */
    public function markAsFailed(string $status, ?\Throwable $e = null)
    {
        $error = [];

        if (!is_null($e)) {
            $error = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
        }

        $this->markAs($status, [
            'error' => $error
        ]);

        BackupWorker::getInstance()->dispatch();
    }

    /**
     * @return void+
     */
    public function calculateTotalItems()
    {
        $finder = $this->getFinder();
        $this->totalItems = $finder->count();
    }

    /**
     * @param bool $withLimit
     * @return \LimitIterator|\Modular\ConnectorDependencies\Symfony\Component\Finder\Finder
     */
    public function getFinder(bool $withLimit = false)
    {
        $path = $this->options->root; // ABSPATH
        $finder = File::getFinder($path, $this->options->excludedFiles);

        if ($withLimit) {
            $finder = new \LimitIterator($finder->getIterator(), $this->offset, $this->options->limit);
        }

        return $finder;
    }

    /**
     * @return string
     */
    public function getZipName(): string
    {
        $suffix = $this->type;

        return sprintf('%s-%s-part-%d', $this->options->name, $suffix, $this->batch);
    }

    /**
     * Get real path of zip file
     *
     * @return string
     */
    public function getZipPath()
    {
        $name = $this->getZipName();

        return Storage::path(Backup::path($name . '.zip'));
    }

    /**
     * @param \ZipArchive $zip
     * @return bool
     * @throws \ErrorException
     */
    public function checkIfBatchSizeIsOversize(\ZipArchive &$zip): bool
    {
        if ($this->batchSize >= $this->options->batchMaxFileSize && $this->offset < $this->totalItems) {
            // Close the zip file after added the files
            File::closeZip($zip);

            // Get real size of zip file
            $this->batchSize = $this->getZipSize();

            if ($this->batchSize >= $this->options->batchMaxFileSize) {
                // Create new part before upload
                $newPart = clone $this;

                $newPart->batchSize = 0;
                $newPart->batch++;
                $newPart->offset++;
                $newPart->status = self::PART_STATUS_PENDING;

                BackupWorker::getInstance()->addPart($newPart);

                // Send current part to upload
                $this->markAsUploadPending();

                $zip = null;

                return true;
            }

            $zip = File::openZip($this->getZipPath());
        }

        return false;
    }

    /**
     * @return int
     */
    public function getZipSize()
    {
        $name = $this->getZipName();
        $path = Backup::path($name . '.zip');

        return Storage::exists($path) ? Storage::size($path) : 0;
    }

    /**
     * Get Upload URL
     *
     * @return mixed
     * @throws \ErrorException
     */
    public function getUploadUri(): string
    {
        $client = OauthClient::getClient();

        return $client->backup->createUpload($this->options->siteBackup, [
            'name' => $this->getZipName(),
            'type' => $this->type,
            'batch' => $this->batch,
        ]);
    }

    /**
     * Check if the part is ready
     *
     * @return void
     * @throws \ErrorException
     */
    public function checkFilesIsReady(?\ZipArchive $zip, $foundFiles)
    {
        // Close the zip file after added the files
        if ($zip instanceof \ZipArchive) {
            File::closeZip($zip);
        }

        $this->batchSize = $this->getZipSize();

        if (!$foundFiles || $this->offset >= $this->totalItems) {
            $this->markAsUploadPending();
        } else if ($this->status === self::PART_STATUS_IN_PROGRESS) {
            $this->update();

            ManagerBackupCompressFilesJob::dispatch($this);
        }
    }

    /**
     * Transform to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->jsonSerialize();
    }

    /**
     * Transform to array
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type,
            'offset' => $this->offset,
            'total_items' => $this->totalItems,
            'batch' => $this->batch,
            'batch_size' => $this->batchSize,
            'status' => $this->status,
            'site_backup' => $this->options->siteBackup,
            'options' => $this->options->toArray()
        ];
    }
}
