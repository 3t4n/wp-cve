<?php

/**
 * Raw Signature Handler
 *
 * PHP version 5
 *
 * Handles signatures as arrays
 *
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2016 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 *
 * Modified by __root__ on 06-December-2022 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Mailster\Gmail\phpseclib3\Crypt\Common\Formats\Signature;

use Mailster\Gmail\phpseclib3\Math\BigInteger;

/**
 * Raw Signature Handler
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class Raw
{
    /**
     * Loads a signature
     *
     * @param array $sig
     * @return array|bool
     */
    public static function load($sig)
    {
        switch (true) {
            case !is_array($sig):
            case !isset($sig['r']) || !isset($sig['s']):
            case !$sig['r'] instanceof BigInteger:
            case !$sig['s'] instanceof BigInteger:
                return false;
        }

        return [
            'r' => $sig['r'],
            's' => $sig['s']
        ];
    }

    /**
     * Returns a signature in the appropriate format
     *
     * @param \Mailster\Gmail\phpseclib3\Math\BigInteger $r
     * @param \Mailster\Gmail\phpseclib3\Math\BigInteger $s
     * @return string
     */
    public static function save(BigInteger $r, BigInteger $s)
    {
        return compact('r', 's');
    }
}
