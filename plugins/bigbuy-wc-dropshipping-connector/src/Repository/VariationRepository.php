<?php

namespace WcMipConnector\Repository;

use WC_Product_Variation;
use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class VariationRepository
{
    private const POST_STATUS = "'publish'";

    /** @var \wpdb */
    protected $wpDb;
    /** @var string */
    private $tableName;
    /** @var string */
    private $postTable;
    /** @var string */
    private $variationMapTable;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
        $this->tableName = $this->wpDb->prefix.ProductRepository::TABLE_NAME;
        $this->postTable = $this->wpDb->prefix.WordpressDatabaseService::TABLE_POSTS;
        $this->variationMapTable = $this->wpDb->prefix.VariationMapRepository::TABLE_NAME;
    }

    /**
     * @return array
     */
    public function getTotalVariations(): array
    {
        $sql = 'SELECT COUNT(*) AS total FROM '.$this->variationMapTable;

        return $this->wpDb->get_row($sql, ARRAY_A);
    }

    /**
     * @return array
     */
    public function getActiveVariations(): array
    {
        $sql = 'SELECT COUNT(*) AS totalActive FROM '.$this->variationMapTable.' pm
            INNER JOIN '.$this->postTable.' pa ON pm.variation_shop_id = pa.ID
            WHERE pa.post_status = '.self::POST_STATUS;

        return $this->wpDb->get_row($sql, ARRAY_A);
    }

    /**
     * @return array
     */
    public function getVariations(): array
    {
        $sql = 'SELECT DISTINCT(pm.variation_id) AS ProductVariationID, p.sku AS SKU, pa.post_status AS Active, p.stock_quantity AS Stock
            FROM '.$this->variationMapTable.' pm 
            INNER JOIN '.$this->tableName.' p ON p.product_id = pm.variation_shop_id 
            INNER JOIN '.$this->postTable.' pa ON pm.variation_shop_id = pa.ID';

        return $this->wpDb->get_results($sql, ARRAY_A);
    }

    /**
     * @param array $variationShopIdsIndexedByProductId
     * @return array
     */
    public function getVariationsUrlIndexedByProductShopId(array $variationShopIdsIndexedByProductId): array
    {
        $variationsUrlIndexedByProductShopId = [];

        foreach ($variationShopIdsIndexedByProductId as $productShopId => $variationShopIds) {
            $variationUrls = [];

            foreach ($variationShopIds as $variationShopId) {
                $productVariation = new WC_Product_Variation($variationShopId);
                $variationUrls['variationShopId'] = $variationShopId;
                $variationUrls['url'] = $productVariation->get_permalink();
                $variationsUrlIndexedByProductShopId[$productShopId][] = $variationUrls;
            }
        }

        return $variationsUrlIndexedByProductShopId;
    }
}