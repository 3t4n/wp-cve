<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class TagMapRepository
{
    public const TABLE_NAME = SystemRepository::DB_PREFIX.'_tag_map';

    /** @var \wpdb  */
    protected $wpDb;
    /** @var string  */
    private $tableName;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
        $this->tableName = $this->wpDb->prefix.self::TABLE_NAME;
    }

    /**
     * @param array $tagMapIds
     *
     * @return array
     */
    public function findByTagMapIdsIndexedByTagMapId(array $tagMapIds): array
    {
        if (empty($tagMapIds)) {
            return [];
        }

        $sql = 'SELECT tag_id, tag_shop_id FROM '.$this->tableName.' WHERE tag_id IN ('.implode(',',$tagMapIds).')';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'tag_shop_id', 'tag_id');
    }

    /**
     * @param array $tagMapIds
     *
     * @return array
     */
    public function findVersionsIndexedByTagMapId(array $tagMapIds): array
    {
        if (empty($tagMapIds)) {
            return [];
        }

        $sql = 'SELECT tag_id, version FROM '.$this->tableName.' WHERE tag_id IN ('.implode(',',$tagMapIds).')';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'version', 'tag_id');
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function save(array $data): bool
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
     * @param string $data
     * @return int
     */
    public function delete(string $data): int
    {
        $sql = 'DELETE FROM '.$this->tableName.' WHERE tag_shop_id IN ('.$data.')';
        return $this->wpDb->query($sql);
    }

    /**
     * @param array $ids
     * @return int
     */
    public function deleteByIds(array $ids): int
    {
        if(empty($ids)){
            return 0;
        }

        $sql = 'DELETE FROM '.$this->tableName.' WHERE tag_shop_id IN ('.implode(',',$ids).')';
        return $this->wpDb->query($sql);
    }

    /**
     * @param array $tagIds
     * @return array
     */
    public function findByTagShopIdsIndexedByTagMapIds(array $tagIds): array
    {
        if (empty($tagIds)) {
            return [];
        }

        $sql = 'SELECT tag_id, tag_shop_id FROM '.$this->tableName.' WHERE tag_shop_id IN ('.implode(',',$tagIds).')';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'tag_shop_id', 'tag_id');
    }
}