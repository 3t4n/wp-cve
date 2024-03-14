<?php

namespace Flamix\Plugin\Queue;

class SQL
{
    private string $table;
    private $prepareClosure;

    /**
     * @param string $table Table name
     * @param callable $prepareClosure Functions to clear SQL injections (->prepare($sql))
     */
    public function __construct(string $table = 'fx_flamix_order_jobs', callable $prepareClosure)
    {
        $this->table = $table;
        $this->prepareClosure = $prepareClosure;
    }

    /**
     * Security prepared SQL.
     *
     * @param ...$args
     * @return string
     */
    private function prepare(...$args): string
    {
        return call_user_func($this->prepareClosure, ...$args);
    }

    /**
     * Get table full name.
     *
     * Different jobs type must have different table.
     *
     * @return string
     */
    protected function getTableName(): string
    {
        return $this->table;
    }

    /**
     * Create table for Queue.
     *
     * @return string
     */
    public function createTable(): string
    {
        return "CREATE TABLE {$this->getTableName()} (
                    id bigint(20) NOT NULL AUTO_INCREMENT,
                    order_id bigint(20) NOT NULL,
                    order_job_status varchar(20) NOT NULL,
                    data text DEFAULT '',
                    attempts tinyint(3) NOT NULL DEFAULT 0,
                    reserved_at datetime DEFAULT NULL,
                    updated_at datetime NOT NULL,
                    created_at datetime NOT NULL,
                PRIMARY KEY (id));";
    }

    /**
     * Use to check is table exist.
     *
     * @return string
     */
    public function describeTable(): string
    {
        return "DESCRIBE {$this->getTableName()}";
    }

    /**
     * Select Queue data.
     *
     * @param array $where
     * @param array $fields
     * @param int|null $limit
     * @return string
     */
    public function select(array $where = [], array $fields = [], ?int $limit = null): string
    {
        $where = $this->arrayToSQL($where);
        $fields = (empty($fields)) ? '*' : implode(',', $fields);
        $limit = ($limit > 0) ? "LIMIT {$limit}" : '';

        return "SELECT {$fields} FROM {$this->getTableName()} WHERE {$where} {$limit};";
    }

    /**
     * Witch JOBs not send.
     *
     * @param string $success
     * @param int $block
     * @param int $limit
     * @return string
     */
    public function notSending(string $success, int $block = 60, int $limit = 20): string
    {
        $time = time() + $block; // 60 seconds block
        $sql = "SELECT id, order_id, UNIX_TIMESTAMP(reserved_at) as reservet_at_timestamp
                    FROM {$this->getTableName()}
                        WHERE order_job_status != '%s' AND (reserved_at IS NULL OR UNIX_TIMESTAMP(reserved_at) < '%d')
                            LIMIT {$limit};";

        return $this->prepare($sql, $success, $time);
    }

    /**
     * Insert JOB to Queue.
     *
     * @param int $order_id
     * @param string $order_job_status
     * @return string
     */
    public function insert(int $order_id, string $order_job_status): string
    {
        $sql = "INSERT INTO {$this->getTableName()} (order_id, order_job_status, updated_at, created_at)
                    VALUES ('%d', '%s', NOW(), NOW());";

        return $this->prepare($sql, $order_id, $order_job_status);
    }

    /**
     * Update Queue JOB.
     *
     * @param array $fields
     * @param array $where
     * @return string
     */
    public function update(array $fields, array $where): string
    {
        $fields = $this->arrayToSQL($fields);
        $where = $this->arrayToSQL($where);
        return "UPDATE {$this->getTableName()} SET {$fields} WHERE {$where};";
    }

    /**
     * Secure convert ['key' => 'value'] to key='value'.
     *
     * @param array $where
     * @return string
     */
    private function arrayToSQL(array $where): string
    {
        if (empty($where))
            return '1=1';

        foreach ($where as $key => &$value)
            switch ($value) {
                case 'increment':
                    $value = "{$key}={$key}+1";
                    break;

                case 'now':
                    $value = "{$key}='" . date('Y-m-d H:i:s') . "'";
                    break;

                default:
                    $value = $this->prepare("{$key}=%s", $value);
                    break;
            }

        return implode(',', $where);
    }
}