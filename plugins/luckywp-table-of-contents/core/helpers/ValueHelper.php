<?php

namespace luckywp\tableOfContents\core\helpers;

class ValueHelper
{

    /**
     * @param mixed $value
     * @return array
     */
    public static function assertArray($value)
    {
        return is_array($value) ? $value : [];
    }

    /**
     * @param $v
     * @return bool
     */
    public static function assertBool($v)
    {
        if (is_bool($v)) {
            return $v;
        }
        $v = strtolower((string)$v);
        return in_array($v, ['1', 'true', 'yes', 'y'], true);
    }
}
