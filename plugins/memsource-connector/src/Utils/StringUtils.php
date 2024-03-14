<?php

namespace Memsource\Utils;

final class StringUtils
{
    private function __construct()
    {
    }

    /**
     * Count size of string.
     *
     * @param string $string
     *
     * @return int
     */
    public static function size($string)
    {
        return function_exists('mb_strlen') ? mb_strlen($string, 'UTF-8') : strlen($string);
    }

    public static function containsText($string): bool
    {
        $string = (string) $string;
        return $string !== "" &&  !ctype_space(preg_replace("/(&nbsp;)/", "", $string));
    }

    /**
     * Convert string to hex.
     *
     * @param string $string
     *
     * @return string
     */
    public static function stringToHex($string)
    {
        $hex = '';
        $max = strlen($string);
        for ($i = 0; $i < $max; $i++) {
            $hex .= dechex(ord($string[$i]));
        }
        return $hex;
    }

    /**
     * Check that given string starts with a substring.
     *
     * @param string $string
     * @param string $substring
     *
     * @return bool
     */
    public static function startsWith($string, $substring): bool
    {
        return substr($string, 0, strlen($substring)) === $substring;
    }

    public static function camelCase($string): string
    {
        if (!is_string($string)) {
            return '';
        }

        return lcfirst(str_replace('_', '', ucwords($string, '_')));
    }
}
