<?php

declare(strict_types=1);

namespace WcMipConnector\Repository;

defined('ABSPATH') || exit;

use WcMipConnector\Service\WordpressDatabaseService;

class ReferenceDataRepository
{
    public const TABLE_NAME = SystemRepository::DB_PREFIX.'_reference_data';

    /** @var \wpdb */
    protected $wpDb;
    /** @var string */
    protected $tableName;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
        $this->tableName = $this->wpDb->prefix.self::TABLE_NAME;
    }

    public function upsert(int $productId, string $reference, ?string $ean = null, ?int $variationId = null): void
    {
        $data = [
            'product_id' => $productId,
            'reference' => $reference,
            'variation_id' => $variationId,
            'ean' => $ean,
        ];

        $this->wpDb->replace($this->tableName, $data);
    }

    public function findEanByReference(string $reference): ?string
    {
        $sql = 'SELECT ean FROM '.$this->tableName.' WHERE reference = "'.$reference.'"';
        $result = $this->wpDb->get_row($sql, \ARRAY_A);

        if ($result === null || !\array_key_exists('ean', $result)) {
            return null;
        }

        return $result['ean'];
    }
}