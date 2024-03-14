<?php

declare(strict_types=1);

namespace CKPL\Pay\Security\JWT;

use CKPL\Pay\Definition\Payload\PayloadInterface;
use CKPL\Pay\Exception\JWTException;
use CKPL\Pay\Security\JWT\Collection\DecodedCollectionInterface;
use CKPL\Pay\Security\JWT\Part\PartInterface;

/**
 * Interface JWTInterface.
 *
 * @package CKPL\Pay\Security\JWT
 */
interface JWTInterface
{
    /**
     * @type int
     */
    const MERCHANT_KEY = 1;

    /**
     * @type int
     */
    const PAYMENT_SERVICE_KEY = 2;

    /**
     * @param PayloadInterface $payload
     *
     * @return string
     */
    public function encode(PayloadInterface $payload): string;

    /**
     * @param string $encodedData
     *
     * @throws JWTException
     *
     * @return DecodedCollectionInterface
     */
    public function decode(string $encodedData): DecodedCollectionInterface;

    /**
     * @param PartInterface ...$parts
     *
     * @return string
     */
    public function sign(PartInterface ...$parts): string;
}
