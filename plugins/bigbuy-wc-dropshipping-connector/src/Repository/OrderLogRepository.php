<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class OrderLogRepository
{
    public const TABLE_NAME = SystemRepository::DB_PREFIX.'_order_log';
    public const WC_ORDER_ITEMS = 'woocommerce_order_items';
    public const WC_STATUS_RETURNED = 'wc-returned';

    /** @var \wpdb */
    protected $wpDb;
    /** @var string */
    protected $tableName;
    /** @var string */
    protected $wcOrderItems;
    /** @var string */
    protected $wcPosts;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
        $this->tableName = $this->wpDb->prefix.self::TABLE_NAME;
        $this->wcOrderItems = $this->wpDb->prefix.self::WC_ORDER_ITEMS;
        $this->wcPosts = $this->wpDb->prefix.WordpressDatabaseService::TABLE_POSTS;
    }

    public function getByOrderId(int $orderId): ?array
    {
        $sql = 'SELECT * FROM '.$this->tableName.' WHERE order_id = "'.$orderId.'"';

        return $this->wpDb->get_row($sql, ARRAY_A);
    }

    public function save(array $data): ?bool
    {
        return $this->wpDb->insert($this->tableName, $data);
    }

    public function update(array $data, array $filter): bool
    {
        return $this->wpDb->update($this->tableName, $data, $filter);
    }

    /**
     * @param string $date
     * @return array|null
     */
    public function getOrdersWithoutShippingByDate(string $date): ?array
    {
        $sql = 'SELECT * FROM '.$this->tableName.' WHERE date_process IS NULL';

        if (!empty($date)) {
            $sql .= " OR `date_add` > '".$date."'";
        }

        $sql .= ' ORDER BY date_add ASC';

        return $this->wpDb->get_results($sql, ARRAY_A);
    }

    public function getOrderIdsNotMappedFilteredByDay(int $days): array
    {
        $sql = "SELECT ID FROM ".$this->wcPosts." 
                WHERE post_date > (NOW() - INTERVAL ".$days." DAY)
                AND post_type = 'shop_order'
                AND post_status <> '".self::WC_STATUS_RETURNED."'
                AND ID NOT IN (SELECT order_id FROM ".$this->tableName.") LIMIT 100";

        $orderIds =  $this->wpDb->get_results($sql, ARRAY_A);

        if (empty($orderIds)) {
            return [];
        }

        return \array_column($orderIds, 'ID', 'ID');
    }

    public function countNotMapped(): int
    {
        $sql = 'SELECT COUNT(*) FROM '.$this->wcOrderItems.' WHERE order_id NOT IN 
                (SELECT order_id FROM '.$this->tableName.' WHERE date_add > (NOW() - INTERVAL 15 DAY))';

        return (int)$this->wpDb->get_var($sql);
    }

    public function countMapped(): int
    {
        $sql = 'SELECT COUNT(*) FROM '.$this->wcOrderItems.' WHERE order_id IN 
                (SELECT order_id FROM '.$this->tableName.' WHERE date_add > (NOW() - INTERVAL 15 DAY))';

        return (int)$this->wpDb->get_var($sql);
    }

    /**
     * @param string $date
     * @return bool
     */
    public function checkIfExistsMoreOrdersToSend(string $date): bool
    {
        $sql = 'SELECT * FROM '.$this->tableName.' WHERE date_add > "'.$date.'" AND date_process IS NULL';

        $result = $this->wpDb->get_results($sql, ARRAY_A);

        return count($result) > 10;
    }
}