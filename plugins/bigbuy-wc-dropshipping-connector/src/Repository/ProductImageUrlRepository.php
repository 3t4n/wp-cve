<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class ProductImageUrlRepository
{
    public const TABLE_NAME = SystemRepository::DB_PREFIX.'_product_image_url';

    /** @var \wpdb */
    private $wpDb;

    /** @var string */
    private $productImageUrlTable;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
        $this->productImageUrlTable = $this->wpDb->prefix.self::TABLE_NAME;
    }

    /**
     * @param array $data
     * @return bool|int
     */
    public function updateProductImageUrl(array $data)
    {
        return $this->wpDb->replace($this->productImageUrlTable, $data);
    }

    /**
     * @param \DateTime $date
     * @return array|object|null
     */
    public function getImagesUrls(\DateTime $date)
    {
        $dateTime = $date->format('Y-m-d H:i:s');
        $sql = 'SELECT distinct (pu.product_shop_id), iu.url, iu.cover, iu.id_image , p.sku
                FROM '.$this->productImageUrlTable.' as piu 
                INNER JOIN '.$this->wpDb->prefix.ProductMapRepository::TABLE_NAME.' as pu 
                    ON pu.product_shop_id = piu.product_shop_id 
                INNER JOIN '.$this->wpDb->prefix.ImagesUrlRepository::TABLE_NAME." as iu 
                    ON iu.id_image = piu.id_image 
                INNER JOIN ".$this->wpDb->prefix.ProductRepository::TABLE_NAME." as p 
                    ON piu.product_shop_id  = p.product_id   
                WHERE iu.date_add > '".$dateTime."' OR iu.date_update > '".$dateTime."';";

        return $this->wpDb->get_results($sql, ARRAY_A);
    }

    public function cleanTable(): void
    {
        $sql = 'DELETE pu.* FROM '.$this->productImageUrlTable.' AS pu
                LEFT JOIN '.$this->wpDb->prefix.ProductRepository::TABLE_NAME.' p ON p.product_id = pu.product_shop_id
                WHERE p.product_id IS NULL;';

        $this->wpDb->query($sql);
    }
}