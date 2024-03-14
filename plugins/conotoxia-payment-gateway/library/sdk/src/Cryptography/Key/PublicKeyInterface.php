<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\Key;

/**
 * Interface PublicKeyInterface.
 *
 * @package CKPL\Pay\Cryptography\Key
 */
interface PublicKeyInterface
{
    /**
     * @return string
     */
    public function getPublicKey(): string;
}
