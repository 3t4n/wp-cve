<?php

namespace Baqend\SDK\Model;

/**
 * Class User created on 25.07.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Model
 */
class User extends Entity
{

    /**
     * @var string
     */
    private $username;

    /**
     * @var bool|null
     */
    private $inactive;

    /**
     * User constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->setUsername('');
        $this->setInactive(false);
    }

    /**
     * Returns the user's username.
     *
     * @return string
     */
    final public function getUsername() {
        return $this->username;
    }

    /**
     * Sets the user's username.
     *
     * @param string $username
     * @return static
     */
    final public function setUsername($username) {
        $this->username = (string) $username;
        return $this;
    }

    /**
     * Determines whether the user is inactive.
     *
     * @return bool|null
     */
    final public function isInactive() {
        return $this->inactive;
    }

    /**
     * Sets whether the user is inactive.
     *
     * @param bool|null $inactive
     * @return static
     */
    final public function setInactive($inactive = null) {
        $this->inactive = (bool) $inactive;
        return $this;
    }
}
