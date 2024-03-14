<?php

namespace Nstaeger\CmsPluginFramework\Support;

use InvalidArgumentException;

class ArgCheck
{
    public static function isArray($arg, $argName = "argument")
    {
        if (!is_array($arg)) {
            self::throwException('an array', $arg, $argName);
        }
    }

    public static function isDateString($arg, $argName = "argument")
    {
        self::isString($arg, $argName);

        $regex = '/^[0-9]{4}-[0-9]{2}-[0-9]{2}/';

        if (preg_match($regex, $arg) != 1) {
            self::throwException('a string containing a date in format yyyy-MM-dd', $arg, $argName);
        }
    }

    public static function isEmail($arg, $argName = "argument")
    {
        self::isString($arg, $argName);

        if (!is_email($arg)) {
            self::throwException('an email', $arg, $argName);
        }
    }

    public static function isInt($arg, $argName = "argument")
    {
        if (!is_int($arg) && !is_numeric($arg)) {
            self::throwException('an integer/numeric', $arg, $argName);
        }
    }

    public static function isIp($arg, $argName = "argument")
    {
        self::isString($arg, $argName);

        if (!filter_var($arg, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)
            && !filter_var($arg, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)
        ) {
            self::throwException('an IP', $arg, $argName);
        }
    }

    public static function isString($arg, $argName = "argument")
    {
        if (!is_string($arg)) {
            self::throwException('a string', $arg, $argName);
        }
    }

    public static function notEmpty($arg, $argName = "argument")
    {
        if (empty($arg)) {
            self::throwException('not empty', $arg, $argName);
        }
    }

    public static function notNull($arg, $argName = "argument")
    {
        if ($arg == null) {
            self::throwException('not null', $arg, $argName);
        }
    }

    private static function throwException($expected, $arg, $argName) {
        throw new InvalidArgumentException(self::generateMessage($expected, $arg, $argName));
    }

    private static function generateMessage($expected, $actual, $argName) {
        return sprintf("Expected %s to be %s, but was: (%s) %s", $argName, $expected, gettype($actual), $actual);
    }
}
