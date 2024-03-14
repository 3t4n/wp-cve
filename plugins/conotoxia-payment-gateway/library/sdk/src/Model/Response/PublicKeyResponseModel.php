<?php

declare(strict_types=1);

namespace CKPL\Pay\Model\Response;

use CKPL\Pay\Endpoint\GetPublicKeysEndpoint;
use CKPL\Pay\Model\ResponseModelInterface;

/**
 * Class PublicKeyResponseModel.
 *
 * @package CKPL\Pay\Model\Response
 */
class PublicKeyResponseModel implements ResponseModelInterface
{
    /**
     * @var string|null
     */
    protected $keyId;

    /**
     * @var string|null
     */
    protected $key;

    /**
     * @return string|null
     */
    public function getKeyId(): ?string
    {
        return $this->keyId;
    }

    /**
     * @param string $keyId
     *
     * @return PublicKeyResponseModel
     */
    public function setKeyId(string $keyId): PublicKeyResponseModel
    {
        $this->keyId = $keyId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @param string $key
     *
     * @return PublicKeyResponseModel
     */
    public function setKey(string $key): PublicKeyResponseModel
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return GetPublicKeysEndpoint::class;
    }
}
