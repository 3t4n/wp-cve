<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class TagRepository
{
    private const TAG_TYPE = 'product_count_product_tag';

    /** @var \wpdb  */
    protected $wpDb;
    /** @var string  */
    private $tableName;
    /** @var string  */
    private $termRelationships;
    /** @var string  */
    private $termTaxonomy;
    /** @var string  */
    private $tagMapTable;
    /** @var string  */
    private $termMetaTable;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
        $this->tableName = $this->wpDb->prefix.WordpressDatabaseService::TABLE_TERMS;
        $this->termRelationships = $this->wpDb->prefix.WordpressDatabaseService::TABLE_TERM_RELATIONSHIPS;
        $this->termTaxonomy = $this->wpDb->prefix.WordpressDatabaseService::TABLE_TERM_TAXONOMY;
        $this->tagMapTable = $this->wpDb->prefix.TagMapRepository::TABLE_NAME;
        $this->termMetaTable = $this->wpDb->prefix.WordpressDatabaseService::TABLE_TERM_META;
    }

    /**
     * @param array $tagShopIds
     *
     * @return array
     */
    public function findByTagShopIdsIndexedByTagId(array $tagShopIds): array
    {
        if (empty($tagShopIds)) {
            return [];
        }

        $sql = 'SELECT ct.term_id, ct.slug  FROM '.$this->tableName.' ct  
         INNER JOIN '.$this->termMetaTable.' tm ON ct.term_id = tm.term_id WHERE tm.meta_key = "'.self::TAG_TYPE.'" AND ct.term_id IN ('.implode(',',$tagShopIds).');';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'term_id', 'term_id');
    }

    /**
     * @deprecated use ApiAdapter
     * @param string $tagName
     *
     * @return string|null
     */
    public function findByName(string $tagName): ?string
    {
        $sql = 'SELECT `term_id` FROM '.$this->tableName.' WHERE `name` = "'.$tagName.'"';
        $result = $this->wpDb->get_row($sql, ARRAY_A);

        if ($result === null || !$result['term_id']) {
            return null;
        }

        return $result['term_id'];
    }

    /**
     * @param array $productIds
     * @return array
     */
    public function getProductTagsIndexedByProductIds(array $productIds): array
    {
        if (empty($productIds)) {
            return [];
        }

        $sql = 'SELECT tr.object_id, tt.term_id FROM '.$this->termRelationships.' tr
                INNER JOIN '.$this->termTaxonomy.' tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                WHERE tt.taxonomy = "product_tag" AND tr.object_id IN ('.implode(',',$productIds).')';

        return (array)$this->wpDb->get_results($sql, ARRAY_A);
    }

    /**
     * @deprecated use ApiAdapter
     * @param string $slug
     *
     * @return string|null
     */
    public function findTagShopIdBySlug(string $slug): ?string
    {
        $sql = 'SELECT `term_id` FROM '.$this->tableName.' WHERE `slug` = "'.$slug.'"';
        $result = $this->wpDb->get_row($sql, ARRAY_A);

        if (!$result['term_id']) {
            return null;
        }

        return $result['term_id'];
    }

    /**
     * @deprecated use ApiAdapter
     * @param array $tagsSlug
     * @return array
     */
    public function findTagsShopIdIndexedBySlug(array $tagsSlug): array
    {
        if (empty($tagsSlug)) {
            return [];
        }

        $sql = 'SELECT ct.term_id, ct.slug  FROM '.$this->tableName.' ct  
         INNER JOIN '.$this->termMetaTable.' tm ON ct.term_id = tm.term_id WHERE tm.meta_key = "'.self::TAG_TYPE.'" AND ct.slug IN ("'.implode('","',$tagsSlug).'")';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'term_id', 'slug');
    }

    /**
     * @return array
     */
    public function findUnusedTagShopIds(): array
    {
        $sql = 'SELECT tm.tag_shop_id
                FROM '.$this->tagMapTable.' tm
                WHERE tm.tag_shop_id IN (
                SELECT tt.term_id
                FROM '.$this->termTaxonomy.' tt
                WHERE tt.taxonomy = "product_tag" AND tt.term_taxonomy_id NOT IN (
                SELECT tr.term_taxonomy_id
                FROM '.$this->termRelationships.' tr));';

        $result = (array)$this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'tag_shop_id', 'tag_shop_id');
    }

    /**
     * @param int $tagId
     */
    public function deleteRelationshipById(int $tagId): void
    {
        $sql = 'DELETE FROM '.$this->termRelationships.' WHERE term_taxonomy_id = (SELECT term_taxonomy_id FROM '.$this->termTaxonomy.' WHERE term_id = '.$tagId.')';

        $this->wpDb->query($sql);
    }
}