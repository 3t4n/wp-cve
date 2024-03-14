<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class ImportProcessProductRepository
{
    public const TABLE_NAME = SystemRepository::DB_PREFIX.'_import_process_product';

    /** @var \wpdb */
    protected $wpDb;
    /** @var string */
    protected $tableName;
    /** @var string */
    protected $fileLogTable;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
        $this->tableName = $this->wpDb->prefix.self::TABLE_NAME;
        $this->fileLogTable = $this->wpDb->prefix.FileLogRepository::TABLE_NAME;
    }

    /**
     * @param array $productIds
     * @param int   $fileId
     *
     * @return array
     */
    public function getProcessedProducts(array $productIds, int $fileId): array
    {
        if (empty($productIds)) {
            return [];
        }

        $sql = 'SELECT product_id FROM '.$this->tableName.' WHERE product_id IN ('.implode(',', $productIds).') AND file_id = '.$fileId.' AND response_api = 1';

        $result = $this->wpDb->get_results($sql);

        return \array_column($result, 'product_id', 'product_id');
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function replace(array $data): bool
    {
        return $this->wpDb->replace($this->tableName, $data);
    }

    /**
     * @param int $fileId
     * @return array
     */
    public function getMaxImportDateProcessed(int $fileId): array
    {
        $sql = 'SELECT MAX(date_update) date_update FROM '.$this->tableName.' WHERE  file_id = '.$fileId;

        return $this->wpDb->get_row($sql, ARRAY_A);
    }

    public function countWithError(): int
    {
        $sql = 'SELECT COUNT(*) FROM '.$this->tableName.' WHERE response_api = 0 AND date_add > (NOW() - INTERVAL 15 DAY)';

        return (int)$this->wpDb->get_var($sql);
    }

    /**
     * @param string $fileName
     * @return array|null
     */
    public function getImportProcessInfo(string $fileName): ?array
    {
        $sql = 'SELECT * FROM '.$this->tableName.' ip INNER JOIN '.$this->fileLogTable.' fl ON ip.file_id = fl.file_id  WHERE fl.name = \''.$fileName.'\'';

        return $this->wpDb->get_results($sql, ARRAY_A);
    }
}