<?php

namespace Flamix\Plugin\Queue;

class JobCommands
{
    /**
     * Is table to store Order Jobs exist?
     *
     * @return bool
     */
    public function isTableExist(): bool
    {
        return $this->query($this->sqlClosure()->describeTable()) !== false;
    }

    /**
     * Create table to store Order Jobs.
     *
     * @return bool
     */
    public function createQueueTableIfNotExist(): bool
    {
        if ($this->isTableExist())
            return true;

        // Create table
        $this->query($this->sqlClosure()->createTable());

        return $this->isTableExist();
    }

    /**
     * Update Job.
     *
     * @param array $fields
     * @param array $where
     * @return mixed
     */
    public function update(array $fields, array $where)
    {
        return $this->query($this->sqlClosure()->update($fields, $where));
    }

    /**
     * Update if order_id exist.
     * Create if order_id not exist.
     *
     * @param $order_id
     * @param array $data
     * @return int
     */
    public function createOrUpdate($order_id, array $data): int
    {
        $id = $this->query($this->sqlClosure()->select(['order_id' => $order_id], ['id'], 1))['0']->id ?? 0;

        // Insert: if we can't find
        if (!$id)
            return $this->query($this->sqlClosure()->insert($order_id, $data['order_job_status'] ?? 'NEW'))['0']->id ?? 0;

        // Update when we found id
        $this->update($data, ['id' => $id]);
        return $id;
    }
}