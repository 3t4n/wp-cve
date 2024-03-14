<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\String;

if (!\function_exists(\WPPayVendor\Symfony\Component\String\u::class)) {
    function u(?string $string = '') : \WPPayVendor\Symfony\Component\String\UnicodeString
    {
        return new \WPPayVendor\Symfony\Component\String\UnicodeString($string ?? '');
    }
}
if (!\function_exists(\WPPayVendor\Symfony\Component\String\b::class)) {
    function b(?string $string = '') : \WPPayVendor\Symfony\Component\String\ByteString
    {
        return new \WPPayVendor\Symfony\Component\String\ByteString($string ?? '');
    }
}
if (!\function_exists(\WPPayVendor\Symfony\Component\String\s::class)) {
    /**
     * @return UnicodeString|ByteString
     */
    function s(?string $string = '') : \WPPayVendor\Symfony\Component\String\AbstractString
    {
        $string = $string ?? '';
        return \preg_match('//u', $string) ? new \WPPayVendor\Symfony\Component\String\UnicodeString($string) : new \WPPayVendor\Symfony\Component\String\ByteString($string);
    }
}
