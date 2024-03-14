<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class CategoryMapRepository
{
    public const TABLE_NAME = SystemRepository::DB_PREFIX.'_category_map';

    /** @var \wpdb */
    protected $wpDb;
    /** @var string */
    protected $tableName;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
        $this->tableName = $this->wpDb->prefix.self::TABLE_NAME;
    }

    /**
     * @param array $categoriesIdToProcess
     *
     * @return array
     */
    public function findAllIndexedByCategoryMapId(array $categoriesIdToProcess): array
    {
        if (empty($categoriesIdToProcess)) {
            return [];
        }

        $sql = 'SELECT category_id, category_shop_id FROM '.$this->tableName.' WHERE category_id IN ('.implode(',',$categoriesIdToProcess).')';

        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'category_shop_id', 'category_id');
    }

    /**
     * @return array
     */
    public function findCategoryShopIndexedByCategoryId(): array
    {
        $sql = 'SELECT category_id, category_shop_id FROM '.$this->tableName;
        $result = (array)$this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'category_shop_id', 'category_id');
    }

    /**
     * @param array $categoriesIdToProcess
     *
     * @return array
     */
    public function findAllIndexedByCategoryShopId(array $categoriesIdToProcess): array
    {
        if (empty($categoriesIdToProcess)) {
            return [];
        }

        $sql = 'SELECT category_shop_id FROM '.$this->tableName.' WHERE category_shop_id IN ('.implode(',',$categoriesIdToProcess).')';

        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'category_shop_id');
    }

    public function findCategoryIdIndexedByCategoryShop(array $categoryShopIds): array
    {
        if (empty($categoryShopIds)) {
            return [];
        }

        $sql = 'SELECT category_shop_id, category_id FROM '.$this->tableName.' WHERE category_shop_id IN ('.implode(',',$categoryShopIds).')';

        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'category_id', 'category_shop_id');
    }

    /**
     * @param array $categoriesIdToProcess
     *
     * @return array
     */
    public function findVersionIndexedByCategoryMapId(array $categoriesIdToProcess): array
    {
        if (empty($categoriesIdToProcess)) {
            return [];
        }

        $sql = 'SELECT category_id, version FROM '.$this->tableName.' WHERE category_id IN ('.implode(',',$categoriesIdToProcess).')';

        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return \array_column($result, 'version', 'category_id');
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
     * @param string $categoryIdList
     * @return int
     */
    public function deleteByCategoryIds(string $categoryIdList): int
    {
        $sql = 'DELETE FROM '.$this->tableName.' WHERE category_id IN ('.$categoryIdList.');';

        return $this->wpDb->query($sql);
    }

    public function cleanTable(): void
    {
        $sql = 'DELETE pcc.* FROM '.$this->tableName.' AS pcc
                LEFT JOIN '.$this->wpDb->prefix.WordpressDatabaseService::TABLE_TERMS.' AS c ON c.term_id = pcc.category_shop_id
                WHERE c.term_id IS NULL;';

        $this->wpDb->query($sql);
    }

    /**
     * @param array $categoryShopIds
     * @return int
     */
    public function deleteByCategoryShopIds(array $categoryShopIds): int
    {
        if (empty($categoryShopIds)) {
            return 0;
        }

        $sql = 'DELETE FROM '.$this->tableName.' WHERE category_shop_id IN ('.\implode(',', $categoryShopIds).');';

        return $this->wpDb->query($sql);
    }
}
