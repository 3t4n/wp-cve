<?php

namespace S2WPImporter;

use S2WPImporter\Traits\ErrorTrait;

class IdMapping
{
    use ErrorTrait;

    /**
     * @var array
     */
    protected $ids = [];

    /**
     * @param int $oldId
     *
     * @return bool
     */
    public function has($oldId)
    {
        return isset($this->ids[$oldId]);
    }

    /**
     * @param int $oldId
     * @param int $newId
     */
    public function add($oldId, $newId)
    {
        if ($this->has($oldId)) {
            $this->addSoftError("{$oldId}: Old ID already exists");
        }

        $this->ids[$oldId] = $newId;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->ids;
    }

    /**
     * @param int $oldId
     *
     * @return false|int
     */
    public function get($oldId)
    {
        if ($this->has($oldId)) {
            return $this->ids[$oldId];
        }

        return false;
    }
}
