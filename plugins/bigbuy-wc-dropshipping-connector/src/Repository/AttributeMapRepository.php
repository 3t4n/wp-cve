<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class AttributeMapRepository
{
    public const TABLE_NAME = SystemRepository::DB_PREFIX.'_attribute_map';

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
     * @param array $attributeMapIds
     *
     * @return array
     */
    public function findByAttributeMapIdsIndexedByAttributeMapId(array $attributeMapIds): array
    {
        if (empty($attributeMapIds)) {
            return [];
        }

        $sql = 'SELECT attribute_id, attribute_shop_id FROM '.$this->tableName.' WHERE attribute_id IN ('.implode(',',$attributeMapIds).')';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'attribute_shop_id', 'attribute_id');
    }

    /**
     * @param array $attributeMapIds
     *
     * @return array
     */
    public function findVersionsIndexedByAttributeMapId(array $attributeMapIds): array
    {
        if (empty($attributeMapIds)) {
            return [];
        }

        $sql = 'SELECT attribute_id, version FROM '.$this->tableName.' WHERE attribute_id IN ('.implode(',',$attributeMapIds).')';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'version', 'attribute_id');
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

    public function cleanTable(): void
    {
        $sql = 'DELETE pca.* FROM '.$this->tableName.' AS pca
                LEFT JOIN '.$this->wpDb->prefix.WordpressDatabaseService::TABLE_TERMS.' AS a ON a.term_id = pca.attribute_shop_id
                WHERE a.term_id IS NULL;';

        $this->wpDb->query($sql);
    }

    /**
     * @param array $attributeShopIds
     * @return int
     */
    public function deleteByAttributeShopIds(array $attributeShopIds): int
    {
        if(empty($attributeShopIds)){
            return 0;
        }

        $sql = 'DELETE FROM '.$this->tableName.' WHERE attribute_shop_id IN ('.\implode(',', $attributeShopIds).');';

        return $this->wpDb->query($sql);
    }
}