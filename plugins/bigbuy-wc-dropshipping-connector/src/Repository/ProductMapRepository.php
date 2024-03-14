<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class ProductMapRepository
{
    public const TABLE_NAME = SystemRepository::DB_PREFIX.'_product_map';

    /** @var \wpdb */
    protected $wpDb;
    /** @var string */
    private $tableName;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
        $this->tableName = $this->wpDb->prefix.self::TABLE_NAME;
    }

    /**
     * @param array $productMapIds
     *
     * @return array
     */
    public function findByProductMapIdsIndexedByProductMapId(array $productMapIds): array
    {
        if (empty($productMapIds)) {
            return [];
        }

        $sql = 'SELECT product_id, product_shop_id FROM '.$this->tableName.' WHERE product_id IN ('.implode(',',$productMapIds).')';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'product_shop_id', 'product_id');
    }

    /**
     * @param string[] $productIds
     * @return array<string, string>
     */
    public function findProductIdsIndexedByProductShopId(array $productIds): array
    {
        if (empty($productIds)) {
            return [];
        }

        $sql = 'SELECT product_id, product_shop_id FROM '.$this->tableName.' WHERE product_id IN ('.implode(',',$productIds).')';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result,  'product_id', 'product_shop_id');
    }

    /**
     * @param array $productMapIds
     *
     * @return array
     */
    public function findVersionsIndexedByProductMapId(array $productMapIds): array
    {
        if (empty($productMapIds)) {
            return [];
        }

        $sql = 'SELECT product_id, version FROM '.$this->tableName.' WHERE product_id IN ('.implode(',',$productMapIds).')';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'version', 'product_id');

    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function save(array $data): bool
    {
        return $this->wpDb->insert($this->tableName, $data);
    }

    /**
     * @param array $data
     * @param array $filter
     * @return bool
     */
    public function update(array $data, array $filter): bool
    {
        return $this->wpDb->update($this->tableName, $data, $filter);
    }

    /**
     * @return array
     */
    public function getProductsShopIndexedByProductShopId(): array
    {
        $sql = 'SELECT pm.product_shop_id FROM '.$this->tableName.' pm';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'product_shop_id', 'product_shop_id');
    }

    /**
     * @param array $productShopIds
     * @return array
     */
    public function findImageVersionIndexedByProductMapId(array $productShopIds): array
    {
        if (empty($productShopIds)) {
            return [];
        }

        $sql = 'SELECT product_id, image_version FROM '.$this->tableName.' WHERE product_shop_id IN ('.implode(',',$productShopIds).')';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'image_version', 'product_id');
    }

    /**
     * @param array $productIdIndexedByProductId
     * @return array
     */
    public function getMessageVersionIndexedByProductId(array $productIdIndexedByProductId): array
    {
        if (empty($productIdIndexedByProductId)) {
            return [];
        }

        $sql = 'SELECT message_version, product_id FROM '.$this->tableName.' WHERE product_id IN ('.implode(',', $productIdIndexedByProductId).')';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'message_version', 'product_id');
    }

    public function cleanTable(): void
    {
        $sql = 'DELETE pcp.* FROM '.$this->tableName.' AS pcp 
                LEFT JOIN '.$this->wpDb->prefix.ProductRepository::TABLE_NAME.' AS p 
                ON p.product_id = pcp.product_shop_id
                WHERE p.product_id IS NULL;';

        $this->wpDb->query($sql);
    }
}