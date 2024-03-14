<?php

class CashBillHelpers
{
    public static function addVarToUrl($url, $key, $value)
    {
        if (strpos($url, '?') === false) {
            return ($url .'?'. $key .'='. $value);
        } else {
            return ($url .'&'. $key .'='. $value);
        }
    }
}
