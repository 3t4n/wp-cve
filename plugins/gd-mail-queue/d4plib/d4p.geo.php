<?php

/*
Name:    d4pLib_Geo
Version: v2.8.13
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2020 Milan Petrovic (email: support@dev4press.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!defined('ABSPATH')) { exit; }

if (!function_exists('d4p_get_ip_whois')) {
    function d4p_get_ip_whois($ip = '') {
        $server = 'whois.lacnic.net';

        if ($ip == '') {
            $ip = d4p_visitor_ip();
        }

        $fp = @fsockopen($server, 43, $errno, $errstr, 20);

        if ($fp === false) {
            return '';
        } else {
            fputs($fp, $ip."\r\n");

            $out = '';
            while (!feof($fp)) {
                $out.= fgets($fp);
            }

            fclose($fp);

            return $out;
        }
    }
}
