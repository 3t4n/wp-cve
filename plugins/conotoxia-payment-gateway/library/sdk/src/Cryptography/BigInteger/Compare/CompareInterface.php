<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\BigInteger\Compare;

/**
 * Interface CompareInterface.
 *
 * @package CKPL\Pay\Cryptography\BigInteger\Compare
 */
interface CompareInterface
{
    /**
     * @return int
     */
    public function perform(): int;
}
