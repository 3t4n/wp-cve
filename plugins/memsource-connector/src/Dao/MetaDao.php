<?php

namespace Memsource\Dao;

use Memsource\Dto\MetaKeyDto;

class MetaDao extends AbstractDao
{
    /** @var string */
    private $postmeta;

    /** @var string */
    private $termmeta;

    public function __construct()
    {
        parent::__construct();
        $this->postmeta = $this->wpdb->postmeta;
        $this->termmeta = $this->wpdb->termmeta;
    }

    /**
     * @return MetaKeyDto[]
     */
    public function findAllMetaKeys(): array
    {
        $sql = "SELECT * FROM (
                    SELECT DISTINCT meta_key AS `name`, '" . MetaKeyDto::TYPE_POST . "' AS `type` FROM $this->postmeta WHERE meta_key NOT LIKE '\_%'
                    UNION ALL
                    SELECT DISTINCT meta_key AS `name`, '" . MetaKeyDto::TYPE_TERM . "' AS `type` FROM $this->termmeta WHERE meta_key NOT LIKE '\_%'
                ) meta
                ORDER BY `type`, `name`";
        return $this->findAll($sql, MetaKeyDto::class);
    }

    /**
     * @return MetaKeyDto[]
     */
    public function findMetaKeys(int $page, int $size): array
    {
        $limit = $size + 1;
        $offset = ($page - 1) * $size;
        $sql = "SELECT * FROM (
                    SELECT DISTINCT meta_key AS `name`, '" . MetaKeyDto::TYPE_POST . "' AS `type` FROM $this->postmeta WHERE meta_key NOT LIKE '\_%'
                    UNION ALL
                    SELECT DISTINCT meta_key AS `name`, '" . MetaKeyDto::TYPE_TERM . "' AS `type` FROM $this->termmeta WHERE meta_key NOT LIKE '\_%'
                ) meta
                ORDER BY `type`, `name`
                LIMIT $limit
                OFFSET $offset";
        return $this->findAll($sql, MetaKeyDto::class);
    }

    /**
     * @return MetaKeyDto[]
     */
    public function findMetaKeysByType(string $type, string $id): array
    {
        $table = $type . 'meta';
        $tableName = $this->wpdb->$table;
        $whereColumn = $type . '_id';
        $query = $this->wpdb->prepare(
            "SELECT meta_id AS id, meta_key AS `name`, meta_value AS `value`, '$type' AS `type` FROM $tableName WHERE $whereColumn = %d",
            $id
        );
        return $this->findAll($query, MetaKeyDto::class);
    }

    public function findByIdAsArray(string $type, string $id): array
    {
        $table = $type . 'meta';
        $tableName = $this->wpdb->$table;
        $whereColumn = $type . '_id';
        $query = $this->wpdb->prepare(
            "SELECT * FROM $tableName WHERE $whereColumn = %d",
            $id
        );
        return $this->findAllAsArray($query);
    }

    public function countAllMetaKeys(): int
    {
        $sql = "SELECT SUM(total) FROM (
                    SELECT COUNT(DISTINCT meta_key) AS total FROM $this->postmeta WHERE meta_key NOT LIKE '\_%'
                    UNION ALL
                    SELECT COUNT(DISTINCT meta_key) AS total FROM $this->termmeta WHERE meta_key NOT LIKE '\_%'
                ) meta";
        return (int) $this->getValue($sql);
    }
}
