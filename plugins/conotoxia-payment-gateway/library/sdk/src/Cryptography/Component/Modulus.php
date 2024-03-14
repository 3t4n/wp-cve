<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\Component;

use function CKPL\Pay\base64url_decode;

/**
 * Class Modulus.
 *
 * @package CKPL\Pay\Cryptography\Component
 */
class Modulus implements ComponentInterface
{
    /**
     * @var string
     */
    protected $modulus;

    /**
     * Modulus constructor.
     *
     * @param string $modulus
     */
    public function __construct(string $modulus)
    {
        $this->modulus = $modulus;
    }

    /**
     * @return string
     */
    public function getComponent(): string
    {
        return $this->modulus;
    }

    /**
     * @return string
     */
    public function getDecodedComponent(): string
    {
        return base64url_decode($this->getComponent());
    }
}
