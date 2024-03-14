<?php

/*
Name:    d4pLib - Functions - File Download
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

if (!function_exists('d4p_readfile')) {
    function d4p_readfile($file_path, $part_size_mb = 2, $return_size = true) {
        $counter = 0;
        $part_size = $part_size_mb * 1024 * 1024;

        $handle = fopen($file_path, 'rb');
        if ($handle === false) {
            return false;
        }

        @set_time_limit(0);
        while (!feof($handle)) {
            $buffer = fread($handle, $part_size);
            echo $buffer;
            flush();

            if ($return_size) {
                $counter+= strlen($buffer);
            }
        }

        $status = fclose($handle);

        if ($return_size && $status) {
            return $counter;
	} else {
            return $status;
        }
    }
}

if (!function_exists('d4p_download_simple')) {
    function d4p_download_simple($file_path, $file_name = null, $gdr_readfile = true) {
        if (is_null($file_name)) {
            $file_name = basename($file_path);
        }

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment; filename=".$file_name.";");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".filesize($file_path));

        if ($gdr_readfile) {
            @d4p_readfile($file_path);
        } else {
            @readfile($file_path);
        }
    }
}

if (!function_exists('d4p_download_resume')) {
    function d4p_download_resume($file_path, $file_name = null) {
        if (is_null($file_name)) {
            $file_name = basename($file_path);
        }

        $fp = @fopen($file_path, 'rb');

        $size = filesize($file_path);
        $length = $size;
        $start = 0;
        $end = $size - 1;

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment; filename=".$file_name.";");
        header("Content-Transfer-Encoding: binary");
        header("Accept-Ranges: 0-$length");

        if (isset($_SERVER['HTTP_RANGE'])) {
            $c_end = $end;

            list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
            if (strpos($range, ',') !== false) {
                header("HTTP/1.1 416 Requested Range Not Satisfiable");
                header("Content-Range: bytes $start-$end/$size");
                exit;
            }

            if ($range[0] == '-') {
                $c_start = $size - substr($range, 1);
            } else {
                $range = explode('-', $range);
                $c_start = $range[0];
                $c_end = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
            }

            $c_end = ($c_end > $end) ? $end : $c_end;
            if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
                header("HTTP/1.1 416 Requested Range Not Satisfiable");
                header("Content-Range: bytes $start-$end/$size");
                exit;
            }

            $start = $c_start;
            $end = $c_end;
            $length = $end - $start + 1;
            fseek($fp, $start);
            header('HTTP/1.1 206 Partial Content');

            header("Content-Range: bytes $start-$end/$size;");
        }

        header("Content-Length: ".$length);

        $buffer = 1024 * 8;
        while(!feof($fp) && ($p = ftell($fp)) <= $end) {
            if ($p + $buffer > $end) {
                $buffer = $end - $p + 1;
            }

            set_time_limit(0);
            echo fread($fp, $buffer);
            flush();
        }

        fclose($fp);
    }
}
