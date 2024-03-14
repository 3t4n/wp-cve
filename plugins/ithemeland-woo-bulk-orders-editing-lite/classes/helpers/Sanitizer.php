<?php

namespace wobel\classes\helpers;

class Sanitizer
{
    public static function array($val)
    {
        $sanitized = null;
        if (is_array($val)) {
            if (count($val) > 0) {
                foreach ($val as $key => $value) {
                    $sanitized[$key] = (is_array($value)) ? self::array($value) : sprintf("%s", stripslashes($value));
                }
            }
        } else {
            $sanitized = sprintf("%s", stripslashes($val));
        }
        return $sanitized;
    }

    public static function number($input)
    {
        return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
}
