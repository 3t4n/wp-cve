<?php

declare(strict_types=1);

namespace CKPL\Pay\Client\RawOutput;

use CKPL\Pay\Definition\Header\HeaderInterface;
use CKPL\Pay\Definition\Payload\PayloadInterface;
use CKPL\Pay\Definition\Signature\SignatureInterface;

/**
 * Class RawOutput.
 *
 * @package CKPL\Pay\Client\RawOutput
 */
class RawOutput implements RawOutputInterface
{
    /**
     * @var HeaderInterface|null
     */
    protected $header;

    /**
     * @var PayloadInterface|null
     */
    protected $payload;

    /**
     * @var SignatureInterface|null
     */
    protected $signature;

    /**
     * @var int
     */
    protected $httpStatus;

    /**
     * RawOutput constructor.
     *
     * @param int                $httpStatus
     * @param PayloadInterface   $payload
     * @param HeaderInterface    $header
     * @param SignatureInterface $signature
     */
    public function __construct(
        int $httpStatus,
        PayloadInterface $payload,
        HeaderInterface $header = null,
        SignatureInterface $signature = null
    ) {
        $this->httpStatus = $httpStatus;
        $this->header = $header;
        $this->payload = $payload;
        $this->signature = $signature;
    }

    /**
     * @return HeaderInterface|null
     */
    public function getHeader(): ?HeaderInterface
    {
        return $this->header;
    }

    /**
     * @return PayloadInterface
     */
    public function getPayload(): PayloadInterface
    {
        return $this->payload;
    }

    /**
     * @return SignatureInterface|null
     */
    public function getSignature(): ?SignatureInterface
    {
        return $this->signature;
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }
}
