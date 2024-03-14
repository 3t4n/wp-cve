<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\BigInteger\Utils;

use function pow;

/**
 * Trait PhpIntConfigTrait.
 *
 * @package CKPL\Pay\Cryptography\BigInteger\Utils
 */
trait PhpIntConfigTrait
{
    /**
     * @return int
     */
    protected static function getBase(): int
    {
        return PHP_INT_SIZE === 8 ? 31 : 26;
    }

    /**
     * @return int
     */
    protected static function getBaseFull(): int
    {
        return PHP_INT_SIZE === 8 ? 0x80000000 : 0x4000000;
    }

    /**
     * @return int
     */
    protected static function getMaxDigitX(): int
    {
        return PHP_INT_SIZE === 8 ? 0x7FFFFFFF : 0x3FFFFFF;
    }

    /**
     * @return int
     */
    protected static function getMax10(): int
    {
        return PHP_INT_SIZE === 8 ? 1000000000 : 10000000;
    }

    /**
     * @return int
     */
    protected static function getMax10Length(): int
    {
        return PHP_INT_SIZE === 8 ? 9 : 7;
    }

    /**
     * @return int
     */
    protected static function getMaxDigitY(): int
    {
        return PHP_INT_SIZE === 8 ? pow(2, 62) : pow(2, 52);
    }
}
