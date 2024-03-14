<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class BrandMapRepository
{
    public const TABLE_NAME = SystemRepository::DB_PREFIX.'_brand_map';

    /** @var \wpdb  */
    protected $wpDb;
    /** @var string  */
    protected $tableName;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
        $this->tableName = $this->wpDb->prefix.self::TABLE_NAME;
    }

    /**
     * @param array $brandMapIds
     * @return array
     */
    public function findByBrandMapIdsIndexedByBrandMapId(array $brandMapIds): array
    {
        if (empty($brandMapIds)) {
            return [];
        }

        $sql = 'SELECT brand_id, brand_shop_id FROM '.$this->tableName.' WHERE brand_id IN ('.implode(',',$brandMapIds).')';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'brand_shop_id', 'brand_id');
    }

    /**
     * @param array $brandMapIds
     * @return array
     */
    public function findVersionsIndexedByBrandMapId(array $brandMapIds): array
    {
        if (empty($brandMapIds)) {
            return [];
        }

        $sql = 'SELECT brand_id, version FROM '.$this->tableName.' WHERE brand_id IN ('.implode(',',$brandMapIds).')';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'version', 'brand_id');
    }

    /**
     * @param array $data
     * @return bool
     */
    public function save(array $data): bool
    {
        return $this->wpDb->insert($this->tableName, $data);
    }

    /**
     * @param array $data
     * @param array $filter
     * @return bool
     */
    public function update(array $data, array $filter): bool
    {
        return $this->wpDb->update($this->tableName, $data, $filter);
    }

    public function cleanTable(): void
    {
        $sql = 'DELETE pcm.* FROM '.$this->tableName.' AS pcm 
                LEFT JOIN '.$this->wpDb->prefix.WordpressDatabaseService::TABLE_TERMS.' AS m ON m.term_id = pcm.brand_shop_id 
                WHERE m.term_id IS NULL;';

        $this->wpDb->query($sql);
    }

    /***
     * @param array $brandShopIds
     * @return int
     */
    public function deleteByBrandShopIds(array $brandShopIds): int
    {
        if (empty($brandShopIds)) {
            return 0;
        }

        $sql = 'DELETE FROM '.$this->tableName.' WHERE brand_shop_id IN ('.\implode(',', $brandShopIds).');';

        return $this->wpDb->query($sql);
    }
}