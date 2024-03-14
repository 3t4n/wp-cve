<?php

namespace WcMipConnector\Repository;

defined('ABSPATH') || exit;

use WcMipConnector\Manager\FileLogManager;
use WcMipConnector\Service\WordpressDatabaseService;

class FileLogRepository
{
    public const TABLE_NAME = SystemRepository::DB_PREFIX.'_file_log';

    /** @var \wpdb */
    protected $wpDb;
    /** @var string */
    protected $tableName;
    /** @var string */
    protected $importProcessProductTableName;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
        $this->tableName = $this->wpDb->prefix.self::TABLE_NAME;
        $this->importProcessProductTableName = $this->wpDb->prefix.ImportProcessProductRepository::TABLE_NAME;
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function insert(array $data): bool
    {
        return $this->wpDb->insert($this->tableName, $data);
    }

    /**
     * @param array $data
     * @param array $filter
     *
     * @return bool
     */
    public function update(array $data, array $filter): bool
    {
        return $this->wpDb->update($this->tableName, $data, $filter);
    }

    /**
     * @param int $limit
     * @return array
     */
    public function getFileWithoutProcess(int $limit = 1): array
    {
        $sql = 'SELECT `file_id`, `name`, `version` FROM '.$this->tableName.' WHERE date_process IS NULL AND date_processing_start IS NULL AND in_process = '.FileLogManager::FILE_NOT_IN_PROCESS.' LIMIT '.$limit;

        $result = $this->wpDb->get_results($sql, ARRAY_A);

        if (!$result) {
            return [];
        }

        return $result;
    }

    /**
     * @return string|null
     */
    public function findFirstAddTime(): ?string
    {
        $result = $this->wpDb->get_row('SELECT MIN(date_add) as date FROM ' . $this->tableName, ARRAY_A);

        return $result ? $result['date'] : null;
    }

    /**
     * @return \stdClass|null
     */
    public function getFileIdInProcess(): ?array
    {
        $sql = 'SELECT file_id, date_processing_start FROM '.$this->tableName.' WHERE in_process = '.FileLogManager::FILE_IN_PROCESS.' LIMIT 1';

        return $this->wpDb->get_row($sql, ARRAY_A);
    }

    /**
     * @return bool
     */
    public function getFileInProcess(): bool
    {
        $in_process = FileLogManager::FILE_NOT_IN_PROCESS;
        $sql = 'SELECT in_process AS in_process FROM '.$this->tableName.' WHERE in_process = '.FileLogManager::FILE_IN_PROCESS;

        $result = $this->wpDb->get_row($sql);
        if (empty($result->in_process)) {
            return $in_process;
        }

        $in_process = FileLogManager::FILE_IN_PROCESS;

        return $in_process;
    }

    /**
     * @param int $day
     *
     * @return array
     */
    public function getFileLogNameByDay(int $day): array
    {
        return $this->wpDb->get_results('SELECT `name` FROM '.$this->tableName.' 
            WHERE date_process < (NOW() - INTERVAL '.$day.' DAY) LIMIT 1000', ARRAY_A);
    }

    /**
     * @param array $fileName
     *
     * @return bool
     */
    public function deleteFileLogByFileName(array $fileName): bool
    {
        return $this->wpDb->delete($this->tableName, $fileName);
    }

    /**
     * @param string $fileName
     *
     * @return array|null
     */
    public function getByName(string $fileName): ?array
    {
        $sql = 'SELECT `file_id`, `name`, `version` FROM '.$this->tableName.' WHERE name = \''.$fileName.'\' LIMIT 1';

        return $this->wpDb->get_row($sql, ARRAY_A);
    }

    /**
     * @param string $fileName
     *
     * @return array|null
     */
    public function getStateFileImport(string $fileName): ?array
    {
        $sql = 'SELECT * FROM '.$this->tableName.' WHERE name = \''.$fileName.'\' LIMIT 1';

        return $this->wpDb->get_row($sql, ARRAY_A);
    }

    /**
     * @param string $fileName
     * @return array|null
     */
    public function getDateProcessByFileName(string $fileName): ?array
    {
        $sql = 'SELECT date_process FROM '.$this->tableName.' WHERE name = \''.$fileName.'\' LIMIT 1';

        return $this->wpDb->get_row($sql, ARRAY_A);
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getTotalFilesCount(): int
    {
        $totalFiles = 'SELECT COUNT(*) AS totalFiles FROM ' .$this->tableName;
        $result = $this->wpDb->get_row($totalFiles);

        return (int)$result->totalFiles;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getPendingFilesCount(): int
    {
        $pendingFiles = 'SELECT COUNT(*) AS pendingFiles FROM ' .$this->tableName. ' WHERE date_process IS NULL AND date_processing_start IS NULL';
        $result = $this->wpDb->get_row($pendingFiles);

        return (int)$result->pendingFiles;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getProcessingFilesCount(): int
    {
        $processingFiles = 'SELECT COUNT(*) AS processingFiles FROM ' .$this->tableName .' WHERE in_process = 1';
        $result = $this->wpDb->get_row($processingFiles);

        return (bool)$result->processingFiles;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getLastStartTime(): string
    {
        $lastStartTime = 'SELECT MAX(date_processing_start) AS max_date_processing_start FROM ' .$this->tableName;
        $result = $this->wpDb->get_row($lastStartTime);

        return (string)$result->max_date_processing_start;
    }

    public function getLastProcessedFiles(): array
    {
        $sql = 'SELECT file_id
            FROM '.$this->tableName.'
            WHERE date_process IS NOT NULL
            ORDER BY file_id DESC LIMIT 10;';

        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'file_id');
    }

    public function countProductsProcessed(array $lastProcessedFilesIds): int
    {
        if (!$lastProcessedFilesIds) {
            return 0;
        }

        $sql = 'SELECT COUNT(product_id)
            FROM '.$this->importProcessProductTableName.'
            WHERE file_id IN ('.implode(',', $lastProcessedFilesIds).');';

        return (int)$this->wpDb->get_var($sql);
    }

    public function getLatestProcessingAverageTimes(array $fileIds): array
    {
        $sql = 'SELECT ROUND(time_to_sec(TIMEDIFF(fl.date_process, fl.date_processing_start))) / COUNT(ip.product_id) AS avg
            FROM '.$this->tableName.' AS fl
            INNER JOIN '.$this->importProcessProductTableName.' AS ip 
            ON ip.file_id = fl.file_id 
            WHERE fl.file_id IN ('.implode(',', $fileIds).')
            GROUP BY fl.file_id;';

        $result = (array)$this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'avg');
    }

    public function getLastProcessedDates(): array
    {
        $sql = 'SELECT date_add, date_process FROM ' .$this->tableName. ' WHERE date_process IS NOT NULL ORDER BY date_process DESC LIMIT 1';

        return (array)$this->wpDb->get_row($sql, ARRAY_A);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getLastAddTime(): string
    {
        $lastAddTime = 'SELECT MAX(date_add) AS max_date_add FROM ' .$this->tableName;
        $result = $this->wpDb->get_row($lastAddTime);

        return (string)$result->max_date_add;
    }

    public function getAllFileNames()
    {
        $sql = 'SELECT `name` FROM '.$this->tableName;

        return $this->wpDb->get_results($sql, ARRAY_A);
    }
}

