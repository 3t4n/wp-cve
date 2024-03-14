<?php

namespace Baqend\SDK\Model;

/**
 * Class Role created on 21.12.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Model
 */
class Role extends Entity
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var string[]
     */
    private $users;

    /**
     * Role constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->setName('');
        $this->setUsers([]);
    }

    /**
     * Returns the role's name.
     *
     * @return string
     */
    final public function getName() {
        return $this->name;
    }

    /**
     * Sets the role's name.
     *
     * @param string $name
     * @return static
     */
    final public function setName($name) {
        $this->name = (string) $name;
        return $this;
    }

    /**
     * Returns the role's users.
     *
     * @return string[]
     */
    final public function getUsers() {
        return $this->users;
    }

    /**
     * Sets the role's users.
     *
     * @param string[] $users
     * @return static
     */
    final public function setUsers(array $users = []) {
        $this->users = $users;
        return $this;
    }

    /**
     * Adds a user to this role.
     *
     * @param string $user
     * @return static
     */
    final public function addUser($user) {
        $this->users[] = $user;
        return $this;
    }

    /**
     * Removes a user from this role.
     *
     * @param string $user
     * @return bool True, if the user could be removed.
     */
    final public function removeUser($user) {
        $index = array_search($user, $this->users, true);
        if ($index === false) {
            return false;
        }

        array_splice($this->users, $index, 1);
        return true;
    }

    /**
     * Determines whether a user belongs to this role.
     *
     * @param User $user
     * @return bool True, if the user belongs to this role.
     */
    final public function hasUser(User $user) {
        return array_search($user, $this->users, true) !== false;
    }
}
