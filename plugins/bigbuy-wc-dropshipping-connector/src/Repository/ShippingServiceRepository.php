<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class ShippingServiceRepository
{
    public const TABLE_NAME = SystemRepository::DB_PREFIX.'_shipping_service';

    /** @var \wpdb */
    protected $wpDb;
    /** @var string  */
    protected $shippingServiceTable;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
        $this->shippingServiceTable = $this->wpDb->prefix.self::TABLE_NAME;
    }

    /**
     * @return array
     */
    public function getAllIndexedById(): array
    {
        $sql = 'SELECT id FROM '.$this->shippingServiceTable.';';

        $result = $this->wpDb->get_results($sql, ARRAY_A);

        if (!$result) {
            return [];
        }

        return \array_column($result, 'id', 'id');
    }

    /**
     * @param string $order
     * @param string|null $orderBy
     * @return array
     */
    public function findAllAndOrderBy(string $order, ?string $orderBy): array
    {
        $sql = 'SELECT * FROM '.$this->shippingServiceTable;

        if ($orderBy !== null) {
            $sql .= ' ORDER BY '.$orderBy.' '.$order;
        }

        $sql .= ';';

        $result = $this->wpDb->get_results($sql, ARRAY_A);

        if (!$result) {
            return [];
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getDisabledNamesIndexedByName(): array
    {
        $sql = 'SELECT name FROM '.$this->shippingServiceTable.' WHERE active = 0;';

        $result = $this->wpDb->get_results($sql, ARRAY_A);

        if (!$result) {
            return [];
        }

        return \array_column($result, 'name', 'name') ;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insert(array $data): bool
    {
        return $this->wpDb->insert($this->shippingServiceTable, $data);
    }

    /**
     * @param array $data
     * @param array $filter
     * @return bool
     */
    public function update(array $data, array $filter): bool
    {
        return $this->wpDb->update($this->shippingServiceTable, $data, $filter);
    }
}