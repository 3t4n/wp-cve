<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class BrandPluginRepository
{
    public const TAXONOMY = 'product_brand'; // WC_Brands_REST_API_V2_Controller

    /** @var \wpdb */
    protected $wpDb;
    /** @var string  */
    protected $tableTerms;
    /** @var string  */
    protected $tableTaxonomies;
    /** @var string */
    private $brandPluginMapTable;
    /** @var string  */
    protected $tableTermTaxonomies;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
        $this->tableTerms = $this->wpDb->prefix.WordpressDatabaseService::TABLE_TERMS;
        $this->tableTaxonomies = $this->wpDb->prefix.AttributeGroupRepository::TABLE_NAME;
        $this->tableTermTaxonomies = $this->wpDb->prefix.WordpressDatabaseService::TABLE_TERM_TAXONOMY;
        $this->brandPluginMapTable = $this->wpDb->prefix.BrandPluginMapRepository::TABLE_NAME;
    }

    /**
     * @param string $slug
     * @return int|null
     */
    public function findBrandShopIdBySlug(string $slug): ?int
    {

        $sql = 'SELECT ct.term_id, ct.slug  FROM '.$this->tableTerms.' ct  
         INNER JOIN '.$this->tableTermTaxonomies.' tm ON ct.term_id = tm.term_id WHERE tm.taxonomy = "'.self::TAXONOMY.'" AND ct.slug = "'.$slug.'";';

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
         INNER JOIN '.$this->tableTermTaxonomies.' tm ON ct.term_id = tm.term_id WHERE tm.taxonomy = "'.self::TAXONOMY.'" AND ct.slug IN ("'.implode('","', $brandsSlug).'")';

        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'term_id', 'slug');
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
         INNER JOIN '.$this->tableTermTaxonomies.' tm ON ct.term_id = tm.term_id WHERE tm.taxonomy = "'.self::TAXONOMY.'" AND ct.term_id IN ('.implode(',',$brandShopIds).');';

        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'term_id', 'term_id');
    }

    /**
     * @return array
     */
    public function findEmptyBrands(): array
    {
        $sql = 'SELECT brand_shop_id FROM '.$this->brandPluginMapTable.' WHERE brand_shop_id NOT IN 
                (SELECT term_id FROM '.$this->tableTermTaxonomies.')';

        $result = (array)$this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'brand_shop_id', 'brand_shop_id');
    }
}