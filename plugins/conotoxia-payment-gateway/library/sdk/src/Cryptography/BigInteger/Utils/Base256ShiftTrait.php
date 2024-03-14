<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\BigInteger\Utils;

use function chr;
use function ltrim;
use function ord;
use function str_repeat;
use function strlen;
use function substr;

/**
 * Trait Base256ShiftTrait.
 *
 * @package CKPL\Pay\Cryptography\BigInteger\Utils
 */
trait Base256ShiftTrait
{
    /**
     * @param string $value
     * @param int    $shift
     *
     * @return void
     */
    protected function base256LeftShift(string &$value, int $shift): void
    {
        if (0 === $shift) {
            return;
        }

        $bytesCount = $shift >> 3;
        $shift &= 7;
        $carry = 0;

        for ($i = strlen($value) - 1; $i >= 0; $i--) {
            $temp = ord($value[$i]) << $shift | $carry;
            $value[$i] = chr($temp);
            $carry = $temp >> 8;
        }

        $carry = (0 != $carry) ? chr($carry) : '';
        $value = $carry.$value. str_repeat(chr(0), $bytesCount);
    }

    /**
     * @param string $x
     * @param int    $shift
     *
     * @return string
     */
    protected function base256RightShift(string &$x, int $shift): string
    {
        if (0 === $shift) {
            $x = ltrim($x, chr(0));

            $result = '';
        } else {
            $bytesCount = $shift >> 3;
            $shift &= 7;

            $remainder = '';

            if ($bytesCount) {
                $start = $bytesCount > strlen($x) ? -strlen($x) : -$bytesCount;
                $remainder = substr($x, $start);
                $x = substr($x, 0, -$bytesCount);
            }

            $carry = 0;
            $shifted = 8 - $shift;

            for ($i = 0; $i < (false === $x ? 0 : strlen($x)); $i++) {
                $temp = (ord($x[$i]) >> $shift) | $carry;
                $carry = (ord($x[$i]) << $shifted) & 0xFF;
                $x[$i] = chr($temp);
            }

            $x = false === $x ? '' : ltrim($x, chr(0));
            $remainder = chr($carry >> $shifted).$remainder;
            $result = ltrim($remainder, chr(0));
        }

        return $result;
    }
}
