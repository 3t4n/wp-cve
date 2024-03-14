<?php

declare(strict_types=1);

namespace CKPL\Pay\Definition\Signature;

/**
 * Interface SignatureInterface.
 *
 * @package CKPL\Pay\Client\Decoder\Signature
 */
interface SignatureInterface
{
    /**
     * @return string
     */
    public function getSignature(): string;
}
