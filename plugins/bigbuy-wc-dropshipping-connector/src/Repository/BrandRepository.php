<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class BrandRepository
{
    public const ATTRIBUTE_NAME = 'brand';

    /** @var \wpdb */
    protected $wpDb;
    /** @var string  */
    protected $tableTerms;
    /** @var string  */
    protected $tableTaxonomies;
    /** @var string  */
    private $taxonomyRelationShips;
    /** @var string */
    private $brandMapTable;
    /** @var string */
    private $tableTermTaxonomies;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
        $this->tableTerms = $this->wpDb->prefix.WordpressDatabaseService::TABLE_TERMS;
        $this->tableTaxonomies = $this->wpDb->prefix.AttributeGroupRepository::TABLE_NAME;
        $this->taxonomyRelationShips = $this->wpDb->prefix.WordpressDatabaseService::TABLE_TERM_RELATIONSHIPS;
        $this->brandMapTable = $this->wpDb->prefix.BrandMapRepository::TABLE_NAME;
        $this->tableTermTaxonomies = $this->wpDb->prefix.WordpressDatabaseService::TABLE_TERM_TAXONOMY;
    }

    public static function getTaxonomy()
    {
        return wc_attribute_taxonomy_name(self::ATTRIBUTE_NAME);
    }

    /**
     * @param array $brandShopIds
     * @return array
     */
    public function findByBrandShopIdsIndexedByBrandId(array $brandShopIds): array
    {
        if (empty($brandShopIds)) {
            return [];
        }

        $sql = 'SELECT ct.term_id  FROM '.$this->tableTerms.' ct  
         INNER JOIN '.$this->tableTermTaxonomies.' tm ON ct.term_id = tm.term_id WHERE tm.taxonomy = "'.self::getTaxonomy().'" AND ct.term_id IN ('.implode(',',$brandShopIds).');';

        $result = $this->wpDb->get_results($sql, ARRAY_A);

        if (!$result) {
            return [];
        }

        return \array_column($result, 'term_id', 'term_id');
    }

    /**
     * @param string $slug
     * @return int|null
     */
    public function findBrandShopIdBySlug(string $slug): ?int
    {
        $sql = 'SELECT ct.term_id  FROM '.$this->tableTerms.' ct  
         INNER JOIN '.$this->tableTermTaxonomies.' tm ON ct.term_id = tm.term_id WHERE tm.taxonomy = "'.self::getTaxonomy().'" slug = "'.$slug.'";';

        $result = $this->wpDb->get_row($sql, ARRAY_A);

        if (!$result['term_id']) {
            return null;
        }

        return (int)$result['term_id'];
    }

    /**
     * @param array $brandsSlug
     * @return array
     */
    public function findBrandShopIdIndexedBySlug(array $brandsSlug): array
    {
        if (empty($brandsSlug)) {
            return [];
        }

        $sql = 'SELECT ct.term_id, ct.slug  FROM '.$this->tableTerms.' ct  
         INNER JOIN '.$this->tableTermTaxonomies.' tm ON ct.term_id = tm.term_id WHERE tm.taxonomy = "'.self::getTaxonomy().'" AND ct.slug IN ("'.implode('","', $brandsSlug).'")';

        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'term_id', 'slug');
    }

    /**
     * @return array
     */
    public function findEmptyAttributeBrands(): array
    {
        $sql = 'SELECT bm.brand_shop_id
                FROM '.$this->brandMapTable.' bm
                WHERE bm.brand_shop_id IN (
                SELECT tt.term_id
                FROM '.$this->tableTermTaxonomies.' tt
                WHERE tt.taxonomy = "pa_brand" AND tt.term_taxonomy_id NOT IN (
                SELECT tr.term_taxonomy_id
                FROM '.$this->taxonomyRelationShips.' tr));';

        $result = (array)$this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'brand_shop_id', 'brand_shop_id');
    }

    /**
     * @param string $label
     * @return int|null
     */
    public function findIdByLabel(string $label): ?int
    {
        $sql = 'SELECT attribute_id FROM '.$this->tableTaxonomies.' WHERE attribute_label = "'.$label.'"';

        $result = $this->wpDb->get_row($sql, ARRAY_A);

        if (!$result) {
            return null;
        }

        return (int)$result['attribute_id'];
    }

    /**
     * @return int|null
     */
    public function findIdByName(): ?int
    {
        $sql = 'SELECT attribute_id FROM '.$this->tableTaxonomies.' WHERE attribute_name = "'.self::ATTRIBUTE_NAME.'"';

        $result = $this->wpDb->get_row($sql, ARRAY_A);

        if (!$result) {
            return null;
        }

        return (int)$result['attribute_id'];
    }
}