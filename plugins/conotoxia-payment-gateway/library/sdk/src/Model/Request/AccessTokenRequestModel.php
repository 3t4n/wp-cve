<?php

declare(strict_types=1);

namespace CKPL\Pay\Model\Request;

use CKPL\Pay\Endpoint\AuthenticationEndpoint;
use CKPL\Pay\Model\RequestModelInterface;

/**
 * Class AccessTokenRequestModel.
 *
 * @package CKPL\Pay\Model\Request
 */
class AccessTokenRequestModel implements RequestModelInterface
{
    /**
     * @var string
     */
    protected $grantType;

    /**
     * @var string
     */
    protected $scope;

    /**
     * AccessTokenRequestModel constructor.
     */
    public function __construct()
    {
        $this->grantType = 'client_credentials';
        $this->scope = 'pay_api';
    }

    /**
     * @return string
     */
    public function getGrantType(): string
    {
        return $this->grantType;
    }

    /**
     * @param string $grantType
     *
     * @return AccessTokenRequestModel
     */
    public function setGrantType(string $grantType): AccessTokenRequestModel
    {
        $this->grantType = $grantType;

        return $this;
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     *
     * @return AccessTokenRequestModel
     */
    public function setScope(string $scope): AccessTokenRequestModel
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return [
            'grant_type' => $this->getGrantType(),
            'scope' => $this->getScope(),
        ];
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return static::FORM;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return AuthenticationEndpoint::class;
    }
}
