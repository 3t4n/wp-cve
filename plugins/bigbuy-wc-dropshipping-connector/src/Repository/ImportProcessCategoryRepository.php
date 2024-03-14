<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class ImportProcessCategoryRepository
{
    public const TABLE_NAME = SystemRepository::DB_PREFIX.'_import_process_category';

    /** @var \wpdb */
    protected $wpDb;
    /** @var string */
    private $tableName;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
        $this->tableName = $this->wpDb->prefix.self::TABLE_NAME;
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
     * @param string $categoryIdList
     * @return int
     */
    public function deleteByCategoryIds(string $categoryIdList): int
    {
        $sql = 'DELETE FROM '.$this->tableName.' WHERE category_id IN ('.$categoryIdList.');';

        return $this->wpDb->query($sql);
    }

    /**
     * @param array $categoriesIds
     * @param int   $fileId
     *
     * @return array
     */
    public function getProcessedCategories(array $categoriesIds, int $fileId): array
    {
        $sql = 'SELECT category_id FROM '.$this->tableName.' WHERE category_id IN ('.implode(',', $categoriesIds).') AND file_id = '.$fileId.' AND response_api = 1';

        $result = $this->wpDb->get_results($sql);

        return \array_column($result, 'category_id', 'category_id');
    }

    public function countWithError(): int
    {
        $sql = 'SELECT COUNT(*) FROM '.$this->tableName.' WHERE response_api = 0 AND date_add > (NOW() - INTERVAL 15 DAY)';

        return (int)$this->wpDb->get_var($sql);
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
}