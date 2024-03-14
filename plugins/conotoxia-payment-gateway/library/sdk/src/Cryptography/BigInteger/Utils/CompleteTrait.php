<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\BigInteger\Utils;

/**
 * Trait CompleteTrait.
 *
 * @package CKPL\Pay\Cryptography\BigInteger\Utils
 */
trait CompleteTrait
{
    /**
     * @param array $value
     * @param int   $summary
     * @param int   $maxDigitX
     *
     * @return void
     */
    protected function completeValue(array &$value, int &$summary, int $maxDigitX): void
    {
        for (; isset($value[$summary]) && $value[$summary] === $maxDigitX; $summary++) {
            $value[$summary] = 0;
        }

        if (!isset($value[$summary])) {
            $value[$summary] = 0;
        }
        $value[$summary]++;
    }
}
