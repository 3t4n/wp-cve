<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\Component;

use function CKPL\Pay\base64url_decode;

/**
 * Class Exponent.
 *
 * @package CKPL\Pay\Cryptography\Component
 */
class Exponent implements ComponentInterface
{
    /**
     * @var string
     */
    protected $exponent;

    /**
     * Exponent constructor.
     *
     * @param string $exponent
     */
    public function __construct(string $exponent)
    {
        $this->exponent = $exponent;
    }

    /**
     * @return string
     */
    public function getComponent(): string
    {
        return $this->exponent;
    }

    /**
     * @return string
     */
    public function getDecodedComponent(): string
    {
        return base64url_decode($this->getComponent());
    }
}
