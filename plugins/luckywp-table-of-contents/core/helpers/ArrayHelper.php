<?php

namespace luckywp\tableOfContents\core\helpers;

use Exception;

class ArrayHelper
{

    /**
     * @param array|object $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue($array, $key, $default = null)
    {
        if (false !== $pos = strrpos($key, '.')) {
            $array = static::getValue($array, substr($key, 0, $pos), $default);
            $key = substr($key, $pos + 1);
        }
        if (is_object($array)) {
            try {
                return $array->$key;
            } catch (Exception $e) {
                return null;
            }
        }
        if (is_array($array)) {
            return array_key_exists($key, $array) ? $array[$key] : $default;
        }
        return $default;
    }

    /**
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function remove(&$array, $key, $default = null)
    {
        if (is_array($array) && array_key_exists($key, $array)) {
            $value = $array[$key];
            unset($array[$key]);
            return $value;
        }
        return $default;
    }

    /**
     * @param array $array
     * @param string $from
     * @param string $to
     * @param string $group
     * @return array
     */
    public static function map($array, $from, $to, $group = null)
    {
        $result = [];
        foreach ($array as $element) {
            $key = static::getValue($element, $from);
            $value = static::getValue($element, $to);
            if ($group !== null) {
                $result[static::getValue($element, $group)][$key] = $value;
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}
