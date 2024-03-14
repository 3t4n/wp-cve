<?php

namespace Memsource\Utils;

class ActionUtils
{
    public static function getParameter($key, $useCookie = true, $defaultValue = null)
    {
        if (isset($_REQUEST[$key])) {
            return $_REQUEST[$key];
        }
        if ($useCookie && isset($_COOKIE[$key])) {
            return $_COOKIE[$key];
        }
        return $defaultValue;
    }
}
