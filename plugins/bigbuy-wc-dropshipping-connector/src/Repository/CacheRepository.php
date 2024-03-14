<?php

declare(strict_types=1);

namespace WcMipConnector\Repository;

defined('ABSPATH') || exit;

use WcMipConnector\Service\WordpressDatabaseService;

class CacheRepository
{
    public const TABLE_NAME = SystemRepository::DB_PREFIX.'_cache';

    /** @var \wpdb */
    protected $wpDb;
    /** @var string */
    protected $tableName;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
        $this->tableName = $this->wpDb->prefix.self::TABLE_NAME;
    }

    public function findOneById(string $itemId): ?string
    {
        if (empty($itemId)) {
            return '';
        }

        $sql = 'SELECT item_data FROM '.$this->tableName.' WHERE item_id = "'.$itemId.'" AND item_expiration_timestamp > (UNIX_TIMESTAMP(CURRENT_TIMESTAMP - INTERVAL 15 MINUTE)) OR item_expiration_timestamp = 0 ORDER BY date_add DESC';
        $result = $this->wpDb->get_row($sql, ARRAY_A);

        if (empty($result)) {
            return '';
        }

        return $result['item_data'];
    }

    public function set(array $data): void
    {
        $this->wpDb->replace($this->tableName, $data);
    }

    public function prune(int $limit): void
    {
        $sql = 'DELETE FROM '.$this->tableName.' WHERE item_expiration_timestamp < (UNIX_TIMESTAMP(CURRENT_TIMESTAMP - INTERVAL 60 MINUTE)) LIMIT '.$limit.';';

        $this->wpDb->query($sql);
    }
}