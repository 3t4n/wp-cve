<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class ImportProcessAttributeGroupRepository
{
    public const TABLE_NAME = SystemRepository::DB_PREFIX.'_import_process_attribute_group';

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
     * @param array $attributesIds
     * @param int   $fileId
     *
     * @return array
     */
    public function getProcessedAttributesGroup(array $attributesIds, int $fileId): array
    {
        $sql = 'SELECT attribute_group_id FROM '.$this->tableName.' WHERE attribute_group_id IN ('.implode(',', $attributesIds).') AND file_id = '.$fileId.' AND response_api = 1';

        $result = $this->wpDb->get_results($sql);

        return \array_column($result, 'attribute_group_id', 'attribute_group_id');
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
        $sql = 'SELECT MAX(date_update) as date_update FROM '.$this->tableName.' WHERE  file_id = '.$fileId;

        return $this->wpDb->get_row($sql, ARRAY_A);
    }
}