<?php

declare(strict_types=1);

namespace CKPL\Pay\Model\Response;

use CKPL\Pay\Endpoint\AuthenticationEndpoint;
use CKPL\Pay\Model\ResponseModelInterface;

/**
 * Class AccessTokenResponseModel.
 *
 * @package CKPL\Pay\Model\Response
 */
class AccessTokenResponseModel implements ResponseModelInterface
{
    /**
     * @var string|null
     */
    protected $accessToken;

    /**
     * @var int|null
     */
    protected $expiresIn;

    /**
     * @var string|null
     */
    protected $tokenType;

    /**
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     *
     * @return AccessTokenResponseModel
     */
    public function setAccessToken(string $accessToken): AccessTokenResponseModel
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getExpiresIn(): ?int
    {
        return $this->expiresIn;
    }

    /**
     * @param int $expiresIn
     *
     * @return AccessTokenResponseModel
     */
    public function setExpiresIn(int $expiresIn): AccessTokenResponseModel
    {
        $this->expiresIn = $expiresIn;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTokenType(): ?string
    {
        return $this->tokenType;
    }

    /**
     * @param string|null $tokenType
     *
     * @return AccessTokenResponseModel
     */
    public function setTokenType(string $tokenType): AccessTokenResponseModel
    {
        $this->tokenType = $tokenType;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return AuthenticationEndpoint::class;
    }
}
