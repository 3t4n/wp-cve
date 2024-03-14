<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\BigInteger\Multiply;

use CKPL\Pay\Cryptography\BigInteger\BigIntegerInterface;
use CKPL\Pay\Exception\BigIntegerException;

/**
 * Interface MultiplyInterface.
 *
 * @package CKPL\Pay\Cryptography\BigInteger\Multiply
 */
interface MultiplyInterface
{
    /**
     * @throws BigIntegerException
     *
     * @return BigIntegerInterface
     */
    public function perform(): BigIntegerInterface;
}
