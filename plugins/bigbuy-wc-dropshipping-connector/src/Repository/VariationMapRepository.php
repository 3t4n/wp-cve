<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class VariationMapRepository
{
    public const TABLE_NAME = SystemRepository::DB_PREFIX.'_variation_map';

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
     * @param array $variationMapIds
     *
     * @return array
     */
    public function findByVariationMapIdsIndexedByVariationMapId(array $variationMapIds): array
    {
        if (empty($variationMapIds)) {
            return [];
        }

        $sql = 'SELECT variation_id, variation_shop_id FROM '.$this->tableName.' WHERE variation_id IN ('.implode(',',$variationMapIds).')';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'variation_shop_id', 'variation_id');
    }

    /**
     * @param string[] $variationIds
     * @return array<string, string>
     */
    public function findVariationIdsIndexedByVariationShopId(array $variationIds):array
    {
        if (empty($variationIds)) {
            return [];
        }

        $sql = 'SELECT variation_id, variation_shop_id FROM '.$this->tableName.' WHERE variation_id IN ('.implode(',',$variationIds).')';
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'variation_id', 'variation_shop_id');
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
     *
     * @return bool
     */
    public function update(array $data, array $filter): bool
    {
        return $this->wpDb->update($this->tableName, $data, $filter);
    }

    /**
     * @return array
     */
    public function getVariationsShopIndexedByVariationShopId(): array
    {
        $sql = 'SELECT variation_shop_id FROM '.$this->tableName;
        $result = $this->wpDb->get_results($sql, ARRAY_A);

        if (empty($result)) {
            return [];
        }

        return \array_column($result, 'variation_shop_id', 'variation_shop_id');
    }
}