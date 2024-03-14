<?php

namespace wobel\classes\helpers;

class Others
{
    public static function array_flatten($array, $sanitize = null)
    {
        if (!is_array($array)) {
            return false;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, self::array_flatten($value, $sanitize));
            } else {
                switch ($sanitize) {
                    case 'int':
                        $result = array_merge($result, array($key => intval($value)));
                        break;
                    default:
                        $result = array_merge($result, array($key => $value));
                        break;
                }
            }
        }
        return $result;
    }

    public static function array_equal($array1, $array2)
    {
        return (is_array($array1)
            && is_array($array2)
            && count($array1) == count($array2)
            && array_diff($array1, $array2) === array_diff($array2, $array1));
    }

    public static function isLocalhost()
    {
        return ($_SERVER['SERVER_NAME'] == 'localhost');
    }

    public static function isAllowedDomain()
    {
        return (in_array($_SERVER['SERVER_NAME'], [
            // 'localhost',
            'wordpress.local',
            'ithemelandco.com',
            'demos.ithemelandco.com'
        ]));
    }
}
