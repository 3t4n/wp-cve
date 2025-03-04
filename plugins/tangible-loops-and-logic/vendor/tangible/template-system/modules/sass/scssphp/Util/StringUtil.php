<?php

/**
 * SCSSPHP
 *
 * @copyright 2012-2020 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://scssphp.github.io/scssphp
 */

namespace Tangible\ScssPhp\Util;

/**
 * @internal
 */
final class StringUtil
{
    public static function trimAsciiRight(string $string, bool $excludeEscape = false): string
    {
        $end = self::lastNonWhitespace($string, $excludeEscape);

        if ($end === null) {
            return '';
        }

        return substr($string, 0, $end + 1);
    }

    /**
     * Returns the index of the last character in $string that's not ASCII
     * whitespace, or `null` if $string is entirely spaces.
     *
     * If $excludeEscape is `true`, this doesn't move past whitespace that's
     * included in a CSS escape.
     */
    private static function lastNonWhitespace(string $string, bool $excludeEscape = false): ?int
    {
        for ($i = \strlen($string) - 1; $i >= 0; $i--) {
            $char = $string[$i];

            if (!Character::isWhitespace($char)) {
                if ($excludeEscape && $i !== 0 && $i !== \strlen($string) && $char === '\\') {
                    return $i + 1;
                }

                return $i;
            }
        }

        return null;
    }

    /**
     * Returns whether $string1 and $string2 are equal, ignoring ASCII case.
     */
    public static function equalsIgnoreCase(?string $string1, string $string2): bool
    {
        if ($string1 === $string2) {
            return true;
        }

        if ($string1 === null) {
            return false;
        }

        return self::toAsciiLowerCase($string1) === self::toAsciiLowerCase($string2);
    }

    /**
     * Converts all ASCII chars to lowercase in the input string.
     *
     * This does not uses `strtolower` because `strtolower` is locale-dependant
     * rather than operating on ASCII.
     * Passing an input string in an encoding that it is not ASCII compatible is
     * unsupported, and will probably generate garbage.
     */
    public static function toAsciiLowerCase(string $string): string
    {
        return strtr($string, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz');
    }
}
