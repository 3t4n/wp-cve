<?php

namespace Memsource\Dao;

use wpdb;

class AbstractDao
{
    /** @var wpdb */
    protected $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    protected function findAll(string $query, string $class): array
    {
        $result = $this->findAllAsArray($query);

        return array_map(static function ($row) use ($class) {
            return new $class($row);
        }, $result);
    }

    protected function findAllAsArray(string $query): array
    {
        $result = $this->wpdb->get_results($query, ARRAY_A);

        if (!is_array($result)) {
            $result = [];
        }

        return $result;
    }

    protected function getValue(string $query): string
    {
        return $this->wpdb->get_var($query) ?? '';
    }

    protected function insert(string $table, array $row)
    {
        $this->wpdb->insert($table, $row);
    }

    protected function update(string $table, array $where, array $row)
    {
        $this->wpdb->update($table, $row, $where);
    }
}
