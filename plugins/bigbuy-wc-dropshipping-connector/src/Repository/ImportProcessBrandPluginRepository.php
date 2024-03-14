<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class ImportProcessBrandPluginRepository
{
    public const TABLE_NAME = SystemRepository::DB_PREFIX.'_import_process_brand_plugin';

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
     * @return bool
     */
    public function replace(array $data): bool
    {
        return $this->wpDb->replace($this->tableName, $data);
    }

    /**
     * @param array $brandsIds
     * @param int $fileId
     * @return array
     */
    public function getProcessedBrands(array $brandsIds, int $fileId): array
    {
        $sql = 'SELECT brand_id FROM '.$this->tableName.' WHERE brand_id IN ('.implode(',', $brandsIds).') AND file_id = '.$fileId.' AND response_api = 1';

        $result = $this->wpDb->get_results($sql);

        return \array_column($result, 'brand_id', 'brand_id');
    }
}