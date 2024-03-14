<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class ImportProcessVariationRepository
{
    public const TABLE_NAME = SystemRepository::DB_PREFIX.'_import_process_variation';

    /** @var \wpdb */
    protected $wpDb;
    /** @var string */
    protected $tableName;

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
     * @param array $variationIds
     * @param int   $fileId
     *
     * @return array
     */
    public function getProcessedVariations(array $variationIds, int $fileId): array
    {
        if (empty($variationIds)) {
            return [];
        }

        $sql = 'SELECT variation_id FROM '.$this->tableName.' WHERE variation_id IN ('.implode(',', $variationIds).') AND file_id = '.$fileId.' AND response_api = 1';

        $result = $this->wpDb->get_results($sql);

        return \array_column($result, 'variation_id', 'variation_id');
    }
}