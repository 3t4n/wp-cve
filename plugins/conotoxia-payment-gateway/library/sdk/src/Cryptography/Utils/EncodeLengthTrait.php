<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\Utils;

use function chr;
use function ltrim;
use function pack;
use function strlen;

/**
 * Trait EncodeLengthTrait.
 *
 * @package CKPL\Pay\Cryptography\Utils
 */
trait EncodeLengthTrait
{
    /**
     * @param int $length
     *
     * @return string
     */
    protected function encodeLength(int $length): string
    {
        if ($length <= 0x7F) {
            $result = chr($length);
        } else {
            $result = ltrim(pack('N', $length), chr(0));
            $result = pack('Ca*', 0x80 | strlen($result), $result);
        }

        return $result;
    }
}
