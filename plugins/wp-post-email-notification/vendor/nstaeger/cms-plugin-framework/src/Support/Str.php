<?php

namespace Nstaeger\CmsPluginFramework\Support;

class Str
{
    /**
     * The cache of snake-cased words.
     *
     * @var array
     */
    protected static $snakeCache = [];

    /**
     * Check if the haystack ends with the needle
     *
     * @param $haystack
     * @param $needle
     * @return bool
     */
    public static function endsWith($haystack, $needle)
    {
        // search forward starting from end minus needle length characters
        return $needle === "" || (
            ($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false
        );
    }

    /**
     * Convert a string to snake case.
     *
     * @param  string $value
     * @param  string $delimiter
     * @return string
     */
    public static function snake($value, $delimiter = '_')
    {
        $key = $value . $delimiter;

        if (isset(static::$snakeCache[$key])) {
            return static::$snakeCache[$key];
        }

        if (!ctype_lower($value)) {
            $value = preg_replace('/\s+/', '', $value);

            $value = strtolower(preg_replace('/(.)(?=[A-Z])/', '$1' . $delimiter, $value));
        }

        return static::$snakeCache[$key] = $value;
    }

    /**
     * Check if the haystack starts with the needle
     *
     * @param $haystack
     * @param $needle
     * @return bool
     */
    public static function startsWith($haystack, $needle)
    {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }
}
