<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography;

use CKPL\Pay\Cryptography\Component\ComponentInterface;
use CKPL\Pay\Cryptography\Key\PublicKeyInterface;

/**
 * Interface PublicKeyCalculatorInterface.
 *
 * @package CKPL\Pay\Cryptography
 */
interface PublicKeyCalculatorInterface
{
    /**
     * @param ComponentInterface $modulus
     * @param ComponentInterface $exponent
     *
     * @return PublicKeyInterface
     */
    public function calculateRsa(ComponentInterface $modulus, ComponentInterface $exponent): PublicKeyInterface;
}
