<?php

/**
 * Finite Field Integer Base Class
 *
 * PHP version 5 and 7
 *
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2017 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 *
 * Modified by __root__ on 06-December-2022 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Mailster\Gmail\phpseclib3\Math\Common\FiniteField;

/**
 * Finite Field Integer
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class Integer implements \JsonSerializable
{
    /**
     * JSON Serialize
     *
     * Will be called, automatically, when json_encode() is called on a BigInteger object.
     *
     * PHP Serialize isn't supported because unserializing would require the factory be
     * serialized as well and that just sounds like too much
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return ['hex' => $this->toHex(true)];
    }

    /**
     * Converts an Integer to a hex string (eg. base-16).
     *
     * @return string
     */
    abstract public function toHex();
}
