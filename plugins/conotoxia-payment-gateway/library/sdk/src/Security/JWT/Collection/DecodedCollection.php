<?php

declare(strict_types=1);

namespace CKPL\Pay\Security\JWT\Collection;

use CKPL\Pay\Definition\Header\HeaderInterface;
use CKPL\Pay\Definition\Payload\PayloadInterface;
use CKPL\Pay\Definition\Signature\SignatureInterface;

/**
 * Class DecodedCollection.
 *
 * @package CKPL\Pay\Security\JWT\Collection
 */
class DecodedCollection implements DecodedCollectionInterface
{
    /**
     * @var HeaderInterface
     */
    protected $header;

    /**
     * @var PayloadInterface
     */
    protected $payload;

    /**
     * @var SignatureInterface
     */
    protected $signature;

    /**
     * DecodedCollection constructor.
     *
     * @param HeaderInterface    $header
     * @param PayloadInterface   $payload
     * @param SignatureInterface $signature
     */
    public function __construct(HeaderInterface $header, PayloadInterface $payload, SignatureInterface $signature)
    {
        $this->header = $header;
        $this->payload = $payload;
        $this->signature = $signature;
    }

    /**
     * @return HeaderInterface
     */
    public function getHeader(): HeaderInterface
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
     * @return SignatureInterface
     */
    public function getSignature(): SignatureInterface
    {
        return $this->signature;
    }
}
