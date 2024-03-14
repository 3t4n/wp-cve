<?php
/********************************************************************
 * @plugin     ModernPolls
 * @file       app/Helpers/AppHelper.php
 * @date       11.02.2021
 * @author     Felix Tzschucke <f.tzschucke@gmail.com>
 * @copyright  2018 - 2021 Felix Tzschucke
 * @license    GPL2
 * @version    1.0.10
 * @link       https://felixtz.de/
 ********************************************************************/

namespace FelixTzWPModernPolls\Helpers;

class AppHelper
{
    public static function removeSlashes($string)
    {
        $string = implode('', explode('\\', $string));
        return stripslashes(trim($string));
    }

    public static function getIpAddress()
    {
        foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return esc_attr($ip);
                    }
                }
            }
        }
    }
}