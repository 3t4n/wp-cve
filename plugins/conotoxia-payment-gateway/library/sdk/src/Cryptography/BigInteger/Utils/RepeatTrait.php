<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\BigInteger\Utils;

use function array_fill;

/**
 * Trait RepeatTrait.
 *
 * @package CKPL\Pay\Cryptography\BigInteger\Utils
 */
trait RepeatTrait
{
    /**
     * Array Repeat.
     *
     * @param int $value
     * @param int $multiplier
     *
     * @return array
     */
    protected function repeat(int $value, int $multiplier): array
    {
        return $multiplier ? array_fill(0, $multiplier, $value) : [];
    }
}
