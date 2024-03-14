<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Manager\FileLogManager;

class FileLogService
{
    /** @var FileLogManager */
    protected $manager;
    /** @var DirectoryService */
    protected $directoryService;

    private const MINIMUN_SECONDS_TO_DELETE_FILE = 604800;
    private const MINIMUN_DAYS_TO_DELETE_FILE_LOG  = 7;
    private const DELETE_ORPHAN_FILES_HOUR = 00;
    private const JSON_FILE_TYPE = '.json';

    /**
     * FileLogService constructor.
     * @param FileLogManager|null $manager
     * @param DirectoryService|null $directoryService
     */
    public function __construct(?FileLogManager $manager = null, ?DirectoryService $directoryService = null)
    {
        $this->manager = $manager;
        $this->directoryService = $directoryService;

        if (!$this->manager) {
            $this->manager = new FileLogManager();
        }

        if (!$this->directoryService) {
            $this->directoryService = new DirectoryService();
        }
    }

    /**
     * @param int $minimDaysToDeleteFileLog
     */
    public function checkOldFileLog(int $minimDaysToDeleteFileLog  = self::MINIMUN_DAYS_TO_DELETE_FILE_LOG ): void
    {
        $fileLogs = $this->manager->getFileLogNameByDay($minimDaysToDeleteFileLog);

        if (!$fileLogs) {
            return;
        }

        foreach ($fileLogs as $fileLog) {
            $fileLocation = $this->directoryService->getImportFilesDir().'/'.$fileLog['name'].'.json';
            $this->manager->deleteFileLogByFileName($fileLog);

            if (file_exists($fileLocation)) {
                unlink($fileLocation);
            }
        }
    }

    /**
     * @param int $minimumSecondsToDeleteFile
     */
    public function deleteLogsByDay(int $minimumSecondsToDeleteFile = self::MINIMUN_SECONDS_TO_DELETE_FILE): void
    {
        $logDir = $this->directoryService->getLogDir();

        $logs = scandir($logDir);
        $currentTime = time();

        foreach ($logs as $log) {
            if ($log !== '.' && $log !== '..' && strpos($log, '.log')) {
                $fileLocation = $logDir.'/'.$log;
                $fileCreationTime = filemtime($fileLocation);
                $dateDifference = $currentTime - $fileCreationTime;

                if ($dateDifference >= $minimumSecondsToDeleteFile) {
                    unlink($fileLocation);
                }
            }
        }
    }

    public function checkIfOrphanFilesMustBeDeleted(): void
    {
        $currentHour = (int)\date('H');

        if ($currentHour !== self::DELETE_ORPHAN_FILES_HOUR) {
            return;
        }

        $this->deleteOrphanFiles();
    }

    /**
     * @return bool
     */
    public function deleteOrphanFiles(): bool
    {
        $firstFileDateInSeconds = \strtotime(date('Y-m-d H:i:s'));

        $filesDir = $this->directoryService->getImportFilesDir();
        $firstFileDate = $this->manager->findFirstAddTime();

        if ($firstFileDate) {
            $firstFileDateInSeconds = \strtotime($firstFileDate);
        }

        $files = $this->directoryService->getFilesByRequiredDir($filesDir);

        if (!$files) {
            return false;
        }

        while($files->valid()) {
            $file = $files->current();

            if ($firstFileDateInSeconds >= $file->getATime() && \strpos($file->getFileName(), '.json')) {
                $this->directoryService->deleteFile($file->getPathname());
            }

            $files->next();
        }

        return true;
    }
}