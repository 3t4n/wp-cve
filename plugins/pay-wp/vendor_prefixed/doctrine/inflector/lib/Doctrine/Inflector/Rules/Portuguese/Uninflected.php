<?php

declare (strict_types=1);
namespace WPPayVendor\Doctrine\Inflector\Rules\Portuguese;

use WPPayVendor\Doctrine\Inflector\Rules\Pattern;
final class Uninflected
{
    /** @return Pattern[] */
    public static function getSingular() : iterable
    {
        yield from self::getDefault();
    }
    /** @return Pattern[] */
    public static function getPlural() : iterable
    {
        yield from self::getDefault();
    }
    /** @return Pattern[] */
    private static function getDefault() : iterable
    {
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('tórax'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('tênis'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('ônibus'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('lápis'));
        (yield new \WPPayVendor\Doctrine\Inflector\Rules\Pattern('fênix'));
    }
}
