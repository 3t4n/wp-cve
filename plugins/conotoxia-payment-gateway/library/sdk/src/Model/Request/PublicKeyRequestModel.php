<?php

declare(strict_types=1);

namespace CKPL\Pay\Model\Request;

use CKPL\Pay\Endpoint\SendPublicKeyEndpoint;
use CKPL\Pay\Model\RequestModelInterface;

/**
 * Class PublicKeyRequestModel.
 *
 * @package CKPL\Pay\Model\Request
 */
class PublicKeyRequestModel implements RequestModelInterface
{
    /**
     * @var string|null
     */
    protected $publicKey;

    /**
     * @return string|null
     */
    public function getPublicKey(): ?string
    {
        return $this->publicKey;
    }

    /**
     * @param string $publicKey
     *
     * @return RequestModelInterface
     */
    public function setPublicKey(string $publicKey): RequestModelInterface
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return SendPublicKeyEndpoint::class;
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return [
            'pem' => $this->publicKey,
        ];
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return static::JSON_OBJECT;
    }
}
