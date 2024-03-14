<?php
/**
 * The AuthToken request model 
 * @since     v1.0
 */
namespace Zahls\Models\Request;

/**
 * Class AuthToken
 * @package Zahls\Models\Request
 */
class AuthToken extends \Zahls\Models\Base
{
    protected $userId = 0;

    /**
     * The user id of the user you want an auth token for
     * 
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set the user id you would like to get an auth token for
     * 
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseModel()
    {
        return new \Zahls\Models\Response\AuthToken();
    }
}
