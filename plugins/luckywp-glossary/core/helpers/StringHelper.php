<?php

namespace luckywp\glossary\core\helpers;

class StringHelper
{

    /**
     * @param string $str
     * @return string
     */
    public static function strtoupper($str)
    {
        return function_exists('mb_strtoupper') ? mb_strtoupper($str) : strtoupper($str);
    }
}
