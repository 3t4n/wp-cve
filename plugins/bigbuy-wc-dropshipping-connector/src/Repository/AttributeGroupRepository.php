<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class AttributeGroupRepository
{
    public const TABLE_NAME = 'woocommerce_attribute_taxonomies';

    /** @var \wpdb  */
    protected $wpDb;
    /** @var string  */
    private $tableName;
    /** @var string */
    private $tableTermTaxonomy;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
        $this->tableName = $this->wpDb->prefix.self::TABLE_NAME;
        $this->tableTermTaxonomy = $this->wpDb->prefix.WordpressDatabaseService::TABLE_TERM_TAXONOMY;
    }

    /**
     * @param array $attributeGroupShopIds
     *
     * @return array
     */
    public function findByAttributeGroupShopIdsIndexedByAttributeGroupId(array $attributeGroupShopIds): array
    {
        if (empty($attributeGroupShopIds)) {
            return [];
        }

        $sql = 'SELECT attribute_id FROM '.$this->tableName.' WHERE attribute_id IN ('.implode(',',$attributeGroupShopIds).')';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'attribute_id', 'attribute_id');
    }

    /**
     * @param string $slug
     * @return int|null
     */
    public function findAttributeGroupShopIdBySlug(string $slug): ?int
    {
        $sql = 'SELECT attribute_id FROM '.$this->tableName.' WHERE attribute_name = "'.$slug.'";';
        $result = $this->wpDb->get_row($sql, ARRAY_A);

        if (!$result['attribute_id']) {
            return null;
        }

        return (int)$result['attribute_id'];
    }

    /**
     * @param array $attributesGroupName
     * @return array
     */
    public function findAttributeGroupShopIdIndexedBySlug(array $attributesGroupName): array
    {
        if (empty($attributesGroupName)) {
            return [];
        }

        $sql = 'SELECT attribute_id, attribute_name FROM '.$this->tableName.' WHERE attribute_name IN ("'.implode('","',$attributesGroupName).'")';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'attribute_id', 'attribute_name');
    }

    /**
     * @param array $attributesGroupIndexedByName
     * @return array
     */
    public function findAttributesGroup(array $attributesGroupIndexedByName): array
    {
        $sql = 'SELECT taxonomy FROM '.$this->tableTermTaxonomy.' WHERE taxonomy IN ("'.implode('","',$attributesGroupIndexedByName).'")';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'taxonomy', 'taxonomy');
    }
}