<?php

declare(strict_types=1);

namespace CKPL\Pay\Security\JWT\Collection;

use CKPL\Pay\Definition\Header\HeaderInterface;
use CKPL\Pay\Definition\Payload\PayloadInterface;
use CKPL\Pay\Definition\Signature\SignatureInterface;

/**
 * Interface DecodedCollectionInterface.
 *
 * @package CKPL\Pay\Security\JWT\Collection
 */
interface DecodedCollectionInterface
{
    /**
     * @return HeaderInterface
     */
    public function getHeader(): HeaderInterface;

    /**
     * @return PayloadInterface
     */
    public function getPayload(): PayloadInterface;

    /**
     * @return SignatureInterface
     */
    public function getSignature(): SignatureInterface;
}
