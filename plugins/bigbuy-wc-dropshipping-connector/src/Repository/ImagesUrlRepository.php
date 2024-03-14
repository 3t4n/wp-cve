<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class ImagesUrlRepository
{
    public const TABLE_NAME = SystemRepository::DB_PREFIX.'_image_url';

    /** @var \wpdb */
    private $wpDb;

    /** @var string */
    private $imageUrlTable;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
        $this->imageUrlTable = $this->wpDb->prefix.self::TABLE_NAME;
    }

    /**
     * @param array $data
     * @return bool|int
     */
    public function updateImagesUrl(array $data)
    {
        return $this->wpDb->replace($this->imageUrlTable, $data);
    }

    public function cleanTable(): void
    {
        $sql = 'DELETE pci.* FROM '.$this->imageUrlTable.' AS pci
                LEFT JOIN '.$this->wpDb->prefix.WordpressDatabaseService::TABLE_POSTS.' i ON i.ID = pci.id_image
                WHERE i.ID IS NULL;';

        $this->wpDb->query($sql);

        $sql = 'DELETE i.*, ppi.* FROM '.$this->imageUrlTable.' AS i
                INNER JOIN '.$this->wpDb->prefix.ProductImageUrlRepository::TABLE_NAME.' AS ppi ON i.id_image = ppi.id_image
                INNER JOIN '.$this->wpDb->prefix.ProductMapRepository::TABLE_NAME.' pm ON ppi.product_shop_id = pm.product_shop_id
                INNER JOIN '.$this->wpDb->prefix.WordpressDatabaseService::TABLE_POSTS.' p ON p.ID = pm.product_shop_id
                WHERE p.post_status = "private";';

        $this->wpDb->query($sql);
    }
}