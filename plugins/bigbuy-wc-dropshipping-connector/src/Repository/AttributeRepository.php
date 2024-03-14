<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class AttributeRepository
{
    /** @var \wpdb  */
    protected $wpDb;
    /** @var string  */
    private $tableName;
    /** @var string  */
    private $attributeMapTable;
    /** @var string  */
    private $taxonomyRelationShips;
    /** @var string  */
    private $taxonomyTerms;
    /** @var string  */
    private $termsMeta;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
        $this->tableName = $this->wpDb->prefix.WordpressDatabaseService::TABLE_TERMS;
        $this->attributeMapTable = $this->wpDb->prefix.AttributeMapRepository::TABLE_NAME;
        $this->taxonomyRelationShips = $this->wpDb->prefix.WordpressDatabaseService::TABLE_TERM_RELATIONSHIPS;
        $this->taxonomyTerms = $this->wpDb->prefix.WordpressDatabaseService::TABLE_TERM_TAXONOMY;
        $this->termsMeta = $this->wpDb->prefix.WordpressDatabaseService::TABLE_TERM_META;
    }

    /**
     * @param array $attributeShopIds
     * @param string $attributeParentName
     * @return array
     */
    public function findByAttributeShopIdsIndexedByAttributeId(array $attributeShopIds, string $attributeParentName): array
    {
        if (empty($attributeShopIds)) {
            return [];
        }

        $sql = 'SELECT ct.term_id  FROM '.$this->tableName.' ct  
         INNER JOIN '.$this->termsMeta.' tm ON ct.term_id = tm.term_id WHERE tm.meta_key = "order_'.$attributeParentName.'" AND ct.term_id IN ('.implode(',',$attributeShopIds).');';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'term_id', 'term_id');
    }

    /**
     * @param string $slug
     * @return null|int
     */
    public function findAttributeShopIdBySlug(string $slug): ?int
    {
        $sql = 'SELECT term_id FROM '.$this->tableName.' WHERE slug = "'.$slug.'";';
        $result = $this->wpDb->get_row($sql, ARRAY_A);

        if (!$result['term_id']) {
            return null;
        }

        return (int)$result['term_id'];
    }

    /**
     * @param array $attributesNames
     * @param string $attributeParentName
     * @return array
     */
    public function findAttributeShopIdIndexedBySlug(array $attributesNames, string $attributeParentName): array
    {
        if (empty($attributesNames)) {
            return [];
        }

        $attributeNameCleaned = [];

        foreach ($attributesNames as $attributeName) {
            $attributeNameCleaned[] = addslashes($attributeName);
        }

        $sql = 'SELECT ct.term_id, ct.slug  FROM '.$this->tableName.' ct  
         INNER JOIN '.$this->termsMeta.' tm ON ct.term_id = tm.term_id WHERE tm.meta_key = "order_'.$attributeParentName.'" AND ct.slug IN ("'.implode('","',$attributeNameCleaned).'")';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'term_id', 'slug');
    }

    /**
     * @return array
     */
    public function findEmptyAttributes(): array
    {
        $sql = 'SELECT am.attribute_shop_id
                FROM '.$this->attributeMapTable.' am
                WHERE am.attribute_shop_id IN (
                SELECT tt.term_id
                FROM '.$this->taxonomyTerms.' tt
                WHERE tt.taxonomy LIKE "pa_attributegroup_%" AND tt.term_taxonomy_id NOT IN (
                SELECT tr.term_taxonomy_id
                FROM '.$this->taxonomyRelationShips.' tr));';

        $result = (array)$this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'attribute_shop_id', 'attribute_shop_id');
    }

    /**
     * @param array $attributeShopIds
     * @return array
     */
    public function findAttributesIndexedByAttributeGroupShopId(array $attributeShopIds): array
    {
        if (empty($attributeShopIds)) {
            return [];
        }

        $sql = 'SELECT term_id, taxonomy FROM '.$this->taxonomyTerms.' WHERE term_id IN ('.implode(',',$attributeShopIds).')';

        return $this->wpDb->get_results($sql, ARRAY_A);
    }
}