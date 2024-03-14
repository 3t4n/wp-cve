<?php

declare(strict_types=1);

namespace CKPL\Pay\Definition\Signature;

/**
 * Class Signature.
 *
 * @package CKPL\Pay\Definition\Signature
 */
class Signature implements SignatureInterface
{
    /**
     * @var string
     */
    protected $signature;

    /**
     * Signature constructor.
     *
     * @param string $signature
     */
    public function __construct(string $signature)
    {
        $this->signature = $signature;
    }

    /**
     * @return string
     */
    public function getSignature(): string
    {
        return $this->signature;
    }
}
