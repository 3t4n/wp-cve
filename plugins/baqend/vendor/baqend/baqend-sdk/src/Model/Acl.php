<?php

namespace Baqend\SDK\Model;

/**
 * Protects the operations on some resource.
 *
 * Class Acl created on 12.11.18.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Model
 */
class Acl
{

    /** @var Permission */
    private $read;

    /** @var Permission */
    private $write;

    /**
     * Acl constructor.
     *
     * @param Permission|null $read
     * @param Permission|null $write
     */
    public function __construct(Permission $read = null, Permission $write = null) {
        $this->read = $read ?: new Permission();
        $this->write = $write ?: new Permission();
    }

    /**
     * @return Permission
     */
    public function getRead() {
        return $this->read;
    }

    /**
     * @return Permission
     */
    public function getWrite() {
        return $this->write;
    }
}
