<?php

namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Helper;

class Core
{
    public function get_current_domain() : string
    {
        if (\function_exists('get_home_url')) {
            $home_url = get_home_url();
            $parsed_url = \parse_url($home_url);
            return $parsed_url['host'] ?? '';
        } else {
            return $_SERVER['HTTP_HOST'] ?? '';
        }
    }
    public function get_visitor_ip() : string
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $re = '/(\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3})/s';
        $matches = [];
        if (\preg_match($re, $ip, $matches, \PREG_OFFSET_CAPTURE, 0) === 1) {
            $ip = $matches[0][0];
        } else {
            $ip = '';
        }
        return $ip;
    }
}
