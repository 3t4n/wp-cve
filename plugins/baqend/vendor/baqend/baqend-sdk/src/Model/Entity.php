<?php

namespace Baqend\SDK\Model;

/**
 * Class Entity created on 25.07.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Model
 */
class Entity
{

    /**
     * @var string|int|null
     */
    private $id;

    /**
     * @var int|null
     */
    private $version;

    /**
     * @var string[]|null
     */
    private $acl;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * Entity constructor.
     *
     * @param string|null $id
     * @param int $version
     * @param \DateTime|null $createdAt
     * @param \DateTime|null $updatedAt
     */
    public function __construct($id = null, $version = 1, \DateTime $createdAt = null, \DateTime $updatedAt = null) {
        $createdAt = $createdAt ?: new \DateTime();
        $updatedAt = $updatedAt ?: $createdAt;

        $this->setId($id);
        $this->setVersion($version);
        $this->setCreatedAt($createdAt);
        $this->setUpdatedAt($updatedAt);
    }

    /**
     * @return string|null
     */
    final public function getId() {
        return $this->id;
    }

    /**
     * @param string|null $id
     * @return static
     */
    final public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|int|null
     */
    final public function retrieveKey() {
        if ($this->id === null) {
            return null;
        }

        if (preg_match('/^\\/db\\/\\w+\\/(\\d+)$/', $this->id, $matches)) {
            list(, $id) = $matches;

            return (int) $id;
        }

        if (preg_match('/^\\/db\\/\\w+\\/([\\w-]+)$/', $this->id, $matches)) {
            list(, $uuid) = $matches;

            return $uuid;
        }

        return null;
    }

    /**
     * @param string|int|null $key
     * @return static
     */
    final public function setKey($key) {
        if ($this->id === null) {
            return $this;
        }

        $notMatched = !preg_match('/^\\/db\\/(\\w+)\\/([\\w-]+)$/', $this->id, $matches);
        if ($key === null || $notMatched) {
            $this->id = null;

            return $this;
        }

        list(, $class) = $matches;
        $this->id = '/db/'.$class.'/'.$key;

        return $this;
    }

    /**
     * @return int
     */
    final public function getVersion() {
        return $this->version;
    }

    /**
     * @param int $version
     * @return static
     */
    final public function setVersion($version) {
        $this->version = $version;
        return $this;
    }

    /**
     * @return string[]|null
     */
    final public function getAcl() {
        return $this->acl;
    }

    /**
     * @param string[]|null $acl
     * @return static
     */
    final public function setAcl($acl) {
        $this->acl = $acl;
        return $this;
    }

    /**
     * @return \DateTime
     */
    final public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return static
     */
    final public function setCreatedAt(\DateTime $createdAt) {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    final public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return static
     */
    final public function setUpdatedAt(\DateTime $updatedAt) {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
