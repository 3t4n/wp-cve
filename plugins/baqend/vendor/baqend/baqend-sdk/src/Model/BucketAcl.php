<?php

namespace Baqend\SDK\Model;

/**
 * Class BucketAcl created on 15.11.18.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Model
 */
class BucketAcl
{

    /** @var Permission */
    private $load;

    /** @var Permission */
    private $insert;

    /** @var Permission */
    private $update;

    /** @var Permission */
    private $delete;

    /** @var Permission */
    private $query;

    /**
     * BucketAcl constructor.
     *
     * @param Permission|null $load   The bucket's initial load permission.
     * @param Permission|null $insert The bucket's initial insert permission.
     * @param Permission|null $update The bucket's initial update permission.
     * @param Permission|null $delete The bucket's initial delete permission.
     * @param Permission|null $query  The bucket's initial query permission.
     */
    public function __construct(
        Permission $load = null,
        Permission $insert = null,
        Permission $update = null,
        Permission $delete = null,
        Permission $query = null
    ) {
        $this->load = $load ?: new Permission();
        $this->insert = $insert ?: new Permission();
        $this->update = $update ?: new Permission();
        $this->delete = $delete ?: new Permission();
        $this->query = $query ?: new Permission();
    }

    /**
     * @return Permission
     */
    public function getLoad() {
        return $this->load;
    }

    /**
     * @return Permission
     */
    public function getInsert() {
        return $this->insert;
    }

    /**
     * @return Permission
     */
    public function getUpdate() {
        return $this->update;
    }

    /**
     * @return Permission
     */
    public function getDelete() {
        return $this->delete;
    }

    /**
     * @return Permission
     */
    public function getQuery() {
        return $this->query;
    }
}
