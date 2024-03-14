<?php

namespace Baqend\SDK\Model;

/**
 * Class UserInfo created on 16.02.2018.
 *
 * @author  Florian Bücklers
 * @package Baqend\SDK\Model
 */
class UserInfo
{
    /**
     * @var string
     */
    private $user;

    /**
     * @var string[]
     */
    private $roles;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $shortLifeEndsAt;

    /**
     * @var string
     */
    private $token;

    /**
     * @return string
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param string $user
     */
    public function setUser($user) {
        $this->user = $user;
    }

    /**
     * @return string[]
     */
    public function getRoles() {
        return $this->roles;
    }

    /**
     * @param string[] $roles
     */
    public function setRoles($roles) {
        $this->roles = $roles;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getShortLifeEndsAt() {
        return $this->shortLifeEndsAt;
    }

    /**
     * @param \DateTime $shortLifeEndsAt
     */
    public function setShortLifeEndsAt($shortLifeEndsAt) {
        $this->shortLifeEndsAt = $shortLifeEndsAt;
    }

    /**
     * @return string
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token) {
        $this->token = $token;
    }
}
