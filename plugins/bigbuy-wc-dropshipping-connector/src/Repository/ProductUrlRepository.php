<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Manager\LanguageReportManager;
use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class ProductUrlRepository
{
    public const TABLE_NAME = SystemRepository::DB_PREFIX.'_product_url';

    /** @var \wpdb */
    private $wpDb;

    /** @var string */
    private $productUrlTable;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
        $this->productUrlTable = $this->wpDb->prefix.self::TABLE_NAME;
    }

    /**
     * @param array $data
     * @return bool|int
     */
    public function updateProductsUrl(array $data)
    {
        return $this->wpDb->replace($this->productUrlTable, $data);
    }

    /**
     * @param \DateTime $date
     * @return array|object|null
     */
    public function getProductUrls(\DateTime $date)
    {
        $dateTime = $date->format('Y-m-d H:i:s');
        $sql = 'SELECT distinct (pm.product_shop_id), pu.variation_shop_id, pu.url, pu.iso_code, p.sku
                FROM '.$this->productUrlTable.' AS pu 
                INNER JOIN '.$this->wpDb->prefix.ProductMapRepository::TABLE_NAME.' as pm 
                    ON pm.product_shop_id = pu.product_shop_id 
                INNER JOIN '.$this->wpDb->prefix.ProductRepository::TABLE_NAME." as p 
                    ON pu.product_shop_id  = p.product_id
                WHERE pu.variation_shop_id = 0 AND (pu.date_update > '".$dateTime."' OR pu.date_add > '".$dateTime."');";

        return $this->wpDb->get_results($sql, ARRAY_A);
    }

    /**
     * @param \DateTime $date
     * @return array|object|null
     */
    public function getVariationsUrls(\DateTime $date)
    {
        $dateTime = $date->format('Y-m-d H:i:s');
        $sql = 'SELECT distinct (pm.product_shop_id), pu.variation_shop_id, pu.url, pu.iso_code, p.sku
                FROM ' .$this->productUrlTable.' AS pu 
                INNER JOIN ' .$this->wpDb->prefix.ProductMapRepository::TABLE_NAME." as pm 
                    ON pm.product_shop_id = pu.product_shop_id 
                INNER JOIN " .$this->wpDb->prefix.ProductRepository::TABLE_NAME. " as p 
                    ON pu.variation_shop_id  = p.product_id
                WHERE pu.date_update > '".$dateTime."' OR pu.date_add > '".$dateTime."';";

        return $this->wpDb->get_results($sql, ARRAY_A);
    }

    public function cleanTable(): void
    {
        $sql = 'DELETE pu.* FROM '.$this->productUrlTable.' AS pu
                LEFT JOIN '.$this->wpDb->prefix.ProductRepository::TABLE_NAME.' p ON p.product_id = pu.product_shop_id
                WHERE p.product_id IS NULL;';

        $this->wpDb->query($sql);

        $sql = 'DELETE u.* FROM '.$this->productUrlTable.' u
                INNER JOIN '.$this->wpDb->prefix.WordpressDatabaseService::TABLE_POSTS.' p ON u.product_shop_id = p.ID
                WHERE p.post_status = "private";';

        $this->wpDb->query($sql);

        $languageManager = new LanguageReportManager();
        $language = explode('_', $languageManager->getDefaultLanguageIsoCode());
        $isoCode = $language[1];

        if (!empty($isoCode)) {
            $sql = 'DELETE u.* FROM '.$this->productUrlTable.' u WHERE u.iso_code != "'.$isoCode.'";';

            $this->wpDb->query($sql);
        }
    }
}