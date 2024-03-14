<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\BigInteger\Utils;

use function count;

/**
 * Trait TrimTrait.
 *
 * @package CKPL\Pay\Cryptography\BigInteger\Utils
 */
trait TrimTrait
{
    /**
     * @param array $value
     *
     * @return array
     */
    protected function trim(array $value): array
    {
        for ($i = count($value) - 1; $i >= 0; $i--) {
            if ($value[$i]) {
                break;
            }

            unset($value[$i]);
        }

        return $value;
    }
}
