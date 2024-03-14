<?php

class RabbitLoader_21_Util_Core
{
    public static function get_param($name, $allowCase = false)
    {
        $val = '';
        if (isset($_GET[$name])) {
            $val = $_GET[$name];
        } else if (isset($_POST[$name])) {
            $val = $_POST[$name];
        }

        if (!empty($val)) {
            if ($allowCase) {
                $val = preg_replace('/[^A-Za-z0-9_\-.]/', '', $val);
            } else {
                $val = sanitize_key($val);
            }
        }

        return $val;
    }

    /**
     * Same as file_put_contents with debug mode wrapper
     */
    public static function fpc($fp, &$data, $debug)
    {
        $cache_dir = RL21UtilWP::get_cache_dir('');
        if (!file_exists($cache_dir)) {
            @mkdir($cache_dir, 0755, true);
        }
        if ($debug) {
            $file_updated = file_put_contents($fp, $data, LOCK_EX);
        } else {
            $file_updated = @file_put_contents($fp, $data, LOCK_EX);
        }
        return $file_updated;
    }

    public static function get_request_type()
    {
        return empty($_SERVER['REQUEST_METHOD']) ? '' : strtolower($_SERVER['REQUEST_METHOD']);
    }

    public static function isDev()
    {
        return ((defined('RABBITLOADER_PLUG_ENV') && RABBITLOADER_PLUG_ENV == 'DEV') || (defined('RABBITLOADER_AC_PLUG_ENV') && RABBITLOADER_AC_PLUG_ENV == 'DEV'));
    }

    public static function serverURINoGet()
    {
        return strtok($_SERVER["REQUEST_URI"], '?');
    }
}
