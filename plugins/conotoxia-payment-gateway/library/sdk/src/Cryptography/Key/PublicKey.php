<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\Key;

/**
 * Class PublicKey.
 *
 * @package CKPL\Pay\Cryptography\Key
 */
class PublicKey implements PublicKeyInterface
{
    /**
     * @var string
     */
    protected $publicKey;

    /**
     * PublicKey constructor.
     *
     * @param string $publicKey
     */
    public function __construct(string $publicKey)
    {
        $this->publicKey = $publicKey;
    }

    /**
     * @return string
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }
}
