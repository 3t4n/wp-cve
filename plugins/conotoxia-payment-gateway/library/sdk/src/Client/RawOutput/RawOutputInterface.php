<?php

declare(strict_types=1);

namespace CKPL\Pay\Client\RawOutput;

use CKPL\Pay\Definition\Header\HeaderInterface;
use CKPL\Pay\Definition\Payload\PayloadInterface;
use CKPL\Pay\Definition\Signature\SignatureInterface;

/**
 * Interface RawOutputInterface.
 *
 * @package CKPL\Pay\Client\RawOutput
 */
interface RawOutputInterface
{
    /**
     * @return HeaderInterface|null
     */
    public function getHeader(): ?HeaderInterface;

    /**
     * @return PayloadInterface
     */
    public function getPayload(): PayloadInterface;

    /**
     * @return SignatureInterface|null
     */
    public function getSignature(): ?SignatureInterface;

    /**
     * @return int
     */
    public function getHttpStatus(): int;
}
