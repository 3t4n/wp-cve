<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Model\FileHealthReport;
use WcMipConnector\Model\FileReport;
use WcMipConnector\Repository\FileLogRepository;
use WcMipConnector\Service\LoggerService;

class FileLogManager
{
    public const FILE_IN_PROCESS = 1;
    public const FILE_NOT_IN_PROCESS = 0;

    /** @var FileLogRepository */
    private $fileLogRepository;

    /** @var LoggerService  */
    private $logger;

    public function __construct()
    {
        $this->fileLogRepository = new FileLogRepository();
        $this->logger = new LoggerService;
    }

    /**
     * @param string $fileName
     * @param string $version
     *
     * @return bool
     */
    public function insert(string $fileName, string $version): bool
    {
        $data = [
            'name' => $fileName,
            'version' => $version,
            'date_add' => date('Y-m-d H:i:s'),
        ];

        return $this->fileLogRepository->insert($data);
    }

    /**
     * @param int $idFile
     *
     * @return bool
     */
    public function updateFileAsProcessed(int $idFile): bool
    {
        $data = [
            'date_process' => date('Y-m-d H:i:s'),
            'in_process' => self::FILE_NOT_IN_PROCESS
        ];

        $where = [
            'file_id' => $idFile,
        ];

        return $this->fileLogRepository->update($data, $where);
    }

    /**
     * @param int $limit
     * @return array
     */
    public function getFileWithoutProcess(int $limit = 1): array
    {
        return $this->fileLogRepository->getFileWithoutProcess($limit);
    }

    /**
     * @return string|null
     */
    public function findFirstAddTime(): ?string
    {
        return $this->fileLogRepository->findFirstAddTime();
    }

    /**
     * @return array|null
     */
    public function getFileIdInProcess(): ?array
    {
        return $this->fileLogRepository->getFileIdInProcess();
    }

    /**
     * @param int $idFile
     *
     * @return bool
     */
    public function updateFileAsInProcess(int $idFile): bool
    {
        $data = [
            'in_process' => self::FILE_IN_PROCESS,
            'date_processing_start' => date('Y-m-d H:i:s')
        ];

        $where = [
            'file_id' => $idFile,
        ];

        return $this->fileLogRepository->update($data, $where);
    }

    /**
     * @return bool
     */
    public function getFileInProcess(): bool
    {
        return $this->fileLogRepository->getFileInProcess();
    }

    /**
     * @param int $day
     *
     * @return array
     */
    public function getFileLogNameByDay(int $day): array
    {
        return $this->fileLogRepository->getFileLogNameByDay($day);
    }

    /**
     * @param array $fileName
     *
     * @return bool
     */
    public function deleteFileLogByFileName(array $fileName): bool
    {
        return $this->fileLogRepository->deleteFileLogByFileName($fileName);
    }

    /**
     * @param string $fileName
     *
     * @return array|null
     */
    public function getByName(string $fileName): ?array
    {
        return $this->fileLogRepository->getByName($fileName);
    }

    /**
     * @param string $fileName
     *
     * @return array|null
     */
    public function getStateFileImport(string $fileName): ?array
    {
        return $this->fileLogRepository->getStateFileImport($fileName);
    }

    /**
     * @param string $fileName
     * @return array|null
     */
    public function getDateProcessByFileName(string $fileName): ?array
    {
        return $this->fileLogRepository->getDateProcessByFileName($fileName);
    }

    /**
     * @param int $fileId
     *
     * @return bool
     */
    public function resetFileState(int $fileId): bool
    {
        $data = [
            'in_process' => self::FILE_NOT_IN_PROCESS,
            'date_processing_start' => null,
            'date_process' => null,
        ];

        $where = [
            'file_id' => $fileId,
        ];

        return $this->fileLogRepository->update($data, $where);
    }

    /**
     * @return FileReport
     * @throws \Exception
     */
    public function getFileReport(): FileReport
    {
        $response = new FileReport();

        $response->TotalFiles = $this->fileLogRepository->getTotalFilesCount();
        $response->PendingFiles = $this->fileLogRepository->getPendingFilesCount();
        $response->ProcessingFiles = $this->fileLogRepository->getProcessingFilesCount();

        $lastStartTime = $this->fileLogRepository->getLastStartTime();

        if ($lastStartTime) {
            $timezone = new \DateTimeZone('UTC');
            $lastStartTime = new \DateTime($lastStartTime, $timezone);
            $response->LastStartTime = $lastStartTime->format(DATE_W3C);
        }else{
            $response->LastStartTime = false;
        }

        $lastAddTime =  $this->fileLogRepository->getLastAddTime();

        if ($lastAddTime) {
            $timezone = new \DateTimeZone('UTC');
            $lastAddTime = new \DateTime($lastAddTime, $timezone);
            $response->LastAddTime = $lastAddTime->format(DATE_W3C);
        }else{
            $response->LastAddTime = false;
        }

        return $response;
    }

    /**
     * @return FileHealthReport
     * @throws \Exception
     */
    public function getFileHealthReport(): FileHealthReport
    {
        $dateAddOfLastImportedFile = null;

        try {
            $dateAddOfLastImportedFile = $this->getLastAddTimeData();
        } catch (\Exception $exception) {
            $this->logger->error(__METHOD__.' Could not retrieve max date from file log. '.$exception->getMessage());
        }

        $lastFileProcessed = $this->getLastProcessedDatesData();
        $lastProcessedFilesIds = $this->getLastProcessedFiles();
        $latestProcessingAverageTimes = $this->getLatestProcessingAverageTimes($lastProcessedFilesIds);
        $latestProcessingAverageTimesCount = \count($latestProcessingAverageTimes);
        $latestProcessingTimePerProductMAX = null;
        $latestProcessingTimePerProductMIN = null;
        $latestProcessingTimePerProductAVG = null;

        if ($latestProcessingAverageTimesCount > 0) {
            $latestProcessingTimePerProductMAX = (float)\max($latestProcessingAverageTimes);
            $latestProcessingTimePerProductMIN = (float)\min($latestProcessingAverageTimes);
            $latestProcessingTimePerProductAVG = (float)\array_sum($latestProcessingAverageTimes) / \count($latestProcessingAverageTimes);
        }

        $response = new FileHealthReport();

        $response->UnprocessedFilesCount = $this->fileLogRepository->getPendingFilesCount();
        $response->DateProcessOfLastProcessedFile = $lastFileProcessed['date_process'];
        $response->DateAddOfLastProcessedFile = $lastFileProcessed['date_add'];
        $response->DateAddOfLastImportedFile = $dateAddOfLastImportedFile;
        $response->LatestProcessingTimePerProductMAX = $latestProcessingTimePerProductMAX;
        $response->LatestProcessingTimePerProductMIN = $latestProcessingTimePerProductMIN;
        $response->LatestProcessingTimePerProductAVG = $latestProcessingTimePerProductAVG;

        $response->TotalProductInLastFiles = $this->countProductsProcessed($lastProcessedFilesIds);

        return $response;
    }

    private function getLastProcessedFiles(): array
    {
        return $this->fileLogRepository->getLastProcessedFiles();
    }

    private function countProductsProcessed(array $lastProcessedFilesIds): int
    {
        if (!$lastProcessedFilesIds) {
            return 0;
        }

        return $this->fileLogRepository->countProductsProcessed($lastProcessedFilesIds);
    }

    private function getLatestProcessingAverageTimes(array $lastProcessedFilesIds): array
    {
        if (!$lastProcessedFilesIds) {
            return [];
        }

        return $this->fileLogRepository->getLatestProcessingAverageTimes($lastProcessedFilesIds);
    }

    private function getLastProcessedDatesData(): array
    {
        $lastProcessedDatesData = $this->fileLogRepository->getLastProcessedDates();
        $timezoneUtc = new \DateTimeZone('UTC');
        $result = ['date_process' => null, 'date_add' => null];

        try {
            if (!empty($lastProcessedDatesData['date_add'])) {
                $dateAdd = new \DateTime($lastProcessedDatesData['date_add'], $timezoneUtc);
                $result['date_add'] = $dateAdd->format(DATE_W3C);
            }
        } catch (\Exception $exception) {
            $this->logger->error(__METHOD__.' Could not retrieve last processed file date_add date. '.$exception->getMessage());
        }

        try {
            if (!empty($lastProcessedDatesData['date_process'])) {
                $dateProcess = new \DateTime($lastProcessedDatesData['date_process'], $timezoneUtc);
                $result['date_process'] = $dateProcess->format(DATE_W3C);
            }
        } catch (\Exception $exception) {
            $this->logger->error(__METHOD__.' Could not retrieve last processed file date_process date. '.$exception->getMessage());
        }

        return $result;
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function getLastAddTimeData(): string
    {
        $lastAddTime = $this->fileLogRepository->getLastAddTime();
        $timezoneUtc = new \DateTimeZone('UTC');

        if (empty($lastAddTime)) {
            throw new \Exception('No last add time data founded in table');
        }

        $dateProcess = new \DateTime($lastAddTime, $timezoneUtc);

        return $dateProcess->format(DATE_W3C);
    }

    public function getAllFileNames()
    {
        return $this->fileLogRepository->getAllFileNames();
    }
}