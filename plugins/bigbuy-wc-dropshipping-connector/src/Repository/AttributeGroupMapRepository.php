<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class AttributeGroupMapRepository
{
    public const TABLE_NAME = SystemRepository::DB_PREFIX.'_attribute_group_map';

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
     * @param array $attributeGroupMapIds
     *
     * @return array
     */
    public function findByAttributeGroupMapIdsIndexedByAttributeGroupMapId(array $attributeGroupMapIds): array
    {
        if (empty($attributeGroupMapIds)) {
            return [];
        }

        $sql = 'SELECT attribute_group_id, attribute_group_shop_id FROM '.$this->tableName.' WHERE attribute_group_id IN ('.implode(',',$attributeGroupMapIds).')';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'attribute_group_shop_id', 'attribute_group_id');
    }

    /**
     * @param array $attributeGroupMapIds
     *
     * @return array
     */
    public function findVersionsIndexedByAttributeGroupMapId(array $attributeGroupMapIds): array
    {
        if (empty($attributeGroupMapIds)) {
            return [];
        }

        $sql = 'SELECT attribute_group_id, version FROM '.$this->tableName.' WHERE attribute_group_id IN ('.implode(',',$attributeGroupMapIds).')';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'version', 'attribute_group_id');
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
     * @return array
     */
    public function getAttributeGroupIdsIndexedByAttributeMapId(): array
    {
        $sql = 'SELECT attribute_group_shop_id, attribute_group_id FROM '.$this->tableName.';';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'attribute_group_shop_id', 'attribute_group_id');
    }

    public function cleanTable(): void
    {
        $sql = 'DELETE pcag.* FROM '.$this->tableName.' AS pcag 
                LEFT JOIN '.$this->wpDb->prefix.AttributeGroupRepository::TABLE_NAME.' AS ag ON ag.attribute_id = pcag.attribute_group_shop_id 
                WHERE ag.attribute_id IS NULL;';

        $this->wpDb->query($sql);
    }

    /**
     * @param array $attributeGroupShopIds
     * @return int
     */
    public function deleteByAttributeGroupShopIds(array $attributeGroupShopIds): int
    {
        if (empty($attributeGroupShopIds)) {
            return 0;
        }

        $sql = 'DELETE FROM '.$this->tableName.' WHERE attribute_group_shop_id IN ('.\implode(',', $attributeGroupShopIds).');';

        return (int)$this->wpDb->query($sql);
    }
}