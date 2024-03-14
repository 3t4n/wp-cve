<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class CategoryRepository
{
    public const TAXONOMY = 'product_cat'; // WC_REST_Product_Categories_V1_Controller

    /**@var \wpdb */
    protected $wpDb;
    /**@var string */
    protected $tableTerms;
    /**@var string */
    protected $categoryMapTable;
    /**@var string */
    protected $taxonomyRelationShips;
    /**@var string */
    protected $postTable;
    /**@var string */
    protected $postMetaTable;
    /**@var string */
    protected $termMetaTable;
    /** @var string  */
    protected $tableTermTaxonomies;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
        $this->tableTerms = $this->wpDb->prefix.WordpressDatabaseService::TABLE_TERMS;
        $this->categoryMapTable = $this->wpDb->prefix.CategoryMapRepository::TABLE_NAME;
        $this->taxonomyRelationShips = $this->wpDb->prefix.WordpressDatabaseService::TABLE_TERM_RELATIONSHIPS;
        $this->postTable = $this->wpDb->prefix.WordpressDatabaseService::TABLE_POSTS;
        $this->postMetaTable = $this->wpDb->prefix.WordpressDatabaseService::TABLE_POST_META;
        $this->termMetaTable = $this->wpDb->prefix.WordpressDatabaseService::TABLE_TERM_META;
        $this->tableTermTaxonomies = $this->wpDb->prefix.WordpressDatabaseService::TABLE_TERM_TAXONOMY;
    }

    /**
     * @deprecated use ApiAdapter
     * @param array $categoryShopIds
     * @return array
     */
    public function findByCategoryShopIdsIndexedByCategoryId(array $categoryShopIds): array
    {
        if (empty($categoryShopIds)) {
            return [];
        }


        $sql = 'SELECT ct.term_id  FROM '.$this->tableTerms.' ct  
         INNER JOIN '.$this->tableTermTaxonomies.' tm ON ct.term_id = tm.term_id WHERE tm.taxonomy = "'.self::TAXONOMY.'" AND ct.term_id IN ('.implode(',',$categoryShopIds).');';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        if (!$result) {
            return [];
        }

        return \array_column($result, 'term_id', 'term_id');
    }

    /**
     * @param array $categoryShopIds
     *
     * @return array
     */
    public function findParentsShopIdsIndexedByShopId(array $categoryShopIds): array
    {
        if (empty($categoryShopIds)) {
            return [];
        }

        $sql = 'SELECT term_id, parent FROM '.$this->tableTermTaxonomies.' WHERE term_id IN ('.implode(',',$categoryShopIds).')';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'parent', 'term_id');
    }

    /**
     * @return array
     */
    public function getTotalCategories(): array
    {
        $sql = 'SELECT COUNT(*) AS total FROM '.$this->categoryMapTable;

        return $this->wpDb->get_row($sql, ARRAY_A);
    }

    /**
     * @return array
     */
    public function getActiveCategories(): array
    {
        $sql = 'SELECT COUNT(*) AS active FROM '.$this->categoryMapTable.' cm
        INNER JOIN '.$this->tableTerms.' ct ON cm.category_shop_id = ct.term_id';

        return $this->wpDb->get_row($sql, ARRAY_A);
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        $sql = 'SELECT cm.category_id AS CategoryID, 1 AS Active FROM '.$this->categoryMapTable.' cm INNER JOIN '.$this->tableTerms.' ct ON ct.term_id = cm.category_shop_id';

        return $this->wpDb->get_results($sql, ARRAY_A);
    }

    /**
     * @return array
     */
    public function findEmptyCategories(): array
    {
        $sql = 'SELECT cm.category_shop_id
                FROM '.$this->categoryMapTable.' cm
                WHERE cm.category_shop_id IN (
                SELECT tt.term_id
                FROM '.$this->tableTermTaxonomies.' tt
                WHERE tt.taxonomy = "product_cat" AND tt.term_taxonomy_id NOT IN (
                SELECT tr.term_taxonomy_id
                FROM '.$this->taxonomyRelationShips.' tr));';

        $result = (array)$this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'category_shop_id', 'category_shop_id');
    }

    /**
     * @deprecated use ApiAdapter
     * @param string $categoryName
     * @return int|null
     */
    public function findCategoryShopIdBySlug(string $categoryName): ?int
    {
        $sql = 'SELECT term_id FROM '.$this->tableTerms.' WHERE slug = "'.$categoryName.'";';
        $result = $this->wpDb->get_row($sql, ARRAY_A);

        if (!$result['term_id']) {
            return null;
        }

        return (int)$result['term_id'];
    }

    /**
     * @param array $categoryShopIds
     * @return array
     */
    public function findCategoryImagePostIdIndexedByCategoryShopId(array $categoryShopIds): array
    {
        if (empty($categoryShopIds)) {
            return [];
        }

        $sql = 'SELECT meta_value, term_id FROM '.$this->termMetaTable.' WHERE term_id IN ('.implode(',', $categoryShopIds).') AND meta_key = "thumbnail_id";';

        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'meta_value', 'term_id');
    }

    /**
     * @deprecated use ApiAdapter
     * @param array $postIds
     * @return array
     */
    public function findImagePostByPostIds(array $postIds): array
    {
        if (empty($postIds)) {
            return [];
        }

        $sql = 'SELECT guid, post_title, ID FROM '.$this->postTable.' WHERE ID IN ('.implode(',', $postIds).') AND post_type = "attachment";';

        return (array)$this->wpDb->get_results($sql, ARRAY_A);
    }

    /**
     * @param int $postId
     * @param string $postTitle
     * @return array
     */
    public function findImagePostIdsIndexedByIds(int $postId, string $postTitle): array
    {
        if (empty($postId)) {
            return [];
        }

        $sql = 'SELECT ID FROM '.$this->postTable.' WHERE ID != '.$postId.' AND post_title LIKE "'.$postTitle.'%" AND post_type = "attachment";';

        $result = $this->wpDb->get_results($sql, ARRAY_A);

        if (!$result) {
            return [];
        }

        return \array_column($result, 'ID', 'ID');
    }

    /**
     * @param array $postIds
     * @return array
     */
    public function findImagePostMetaByPostId(array $postIds): array
    {
        if (empty($postIds)) {
            return [];
        }

        $sql = 'SELECT meta_id, meta_key, meta_value FROM '.$this->postMetaTable.' WHERE post_id IN ('.implode(',', $postIds).');';

        return (array)$this->wpDb->get_results($sql, ARRAY_A);
    }

    /**
     * @param array $postIds
     */
    public function deleteImagePostById(array $postIds): void
    {
        $sql = 'DELETE FROM '.$this->postTable.' WHERE ID IN ('.\implode(',', $postIds).');';

        $this->wpDb->query($sql);
    }

    /**
     * @param array $metaDataIds
     */
    public function deleteImageMetaDataByMetaDataId(array $metaDataIds): void
    {
        $sql = 'DELETE FROM '.$this->postMetaTable.' WHERE meta_id IN ('.\implode(',', $metaDataIds).');';

        $this->wpDb->query($sql);
    }

    /**
     * @param array $postIds
     * @return array
     */
    public function findImagePostIndexedByPostId(array $postIds): array
    {
        if (empty($postIds)) {
            return [];
        }

        $sql = 'SELECT ID, post_title FROM '.$this->postTable.' WHERE ID IN ('.implode(',', $postIds).')  AND post_type = "attachment";';

        $result = $this->wpDb->get_results($sql, ARRAY_A);

        if (!$result) {
            return [];
        }

        return \array_column($result, 'post_title', 'ID');
    }

    /**
     * @param array $postIds
     * @return array
     */
    public function findUrlIndexedByPostId(array $postIds): array
    {
        if (empty($postIds)) {
            return [];
        }

        $sql = 'SELECT ID, guid FROM '.$this->postTable.' WHERE ID IN ('.implode(',', $postIds).')  AND post_type = "attachment";';

        $result = (array)$this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'guid', 'ID');
    }

    /**
     * @deprecated use ApiAdapter
     * @param array $categoriesSlug
     * @return array
     */
    public function findCategoriesShopIdIndexedBySlug(array $categoriesSlug): array
    {
        if (empty($categoriesSlug)) {
            return [];
        }

        $sql = 'SELECT ct.term_id, ct.slug  FROM '.$this->tableTerms.' ct  
         INNER JOIN '.$this->tableTermTaxonomies.' tm ON ct.term_id = tm.term_id WHERE tm.taxonomy = "'.self::TAXONOMY.'" AND ct.slug IN ("'.implode('","',$categoriesSlug).'")';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        if (!$result) {
            return [];
        }

        return \array_column($result, 'term_id', 'slug');
    }

    /**
     * @deprecated use ApiAdapter
     * @param string $categorySlug
     * @return array
     */
    public function findCategoryIdsBySlug(string $categorySlug): array
    {
        if (empty($categorySlug)) {
            return [];
        }
        $sql = 'SELECT term_id, slug FROM '.$this->tableTerms.' WHERE slug LIKE "%'.$categorySlug.'%"';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        if (!$result) {
            return [];
        }

        return \array_column($result, 'slug', 'term_id');
    }
}