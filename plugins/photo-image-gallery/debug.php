<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 3/28/2017
 * Time: 11:03 AM
 */

define("UXGALLERY_DEBUG_ENABLE", true);
define("UXGALLERY_ACCESS_IP", "127.0.0.1");

class  UXGallery_debug
{

    public static function trace($arr)
    {
        $client_ip = self::get_client_ip();

        if ($client_ip == UXGALLERY_ACCESS_IP && UXGALLERY_DEBUG_ENABLE === true) {
            echo "<div><pre style='background-color: #ffff82;padding: 10px;'>";
            print_r($arr);
            echo "</pre></div>";
        }
    }

    public static function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

}