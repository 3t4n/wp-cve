<?php

namespace MinuteMedia;

if (!defined('MINUTE_MEDIA_PSR4')) {
    define('MINUTE_MEDIA_PSR4', realpath(dirname(__FILE__).'/../'));
}

if (!class_exists('MinuteMedia\Autoload')) {
    class Autoload {
        /**
         * @var array Array of allowed class file suffixes
         */
        const SUFFIXARRAY = array('.php', '.class.php', '.inc.php');

        /**
         * Looks for a class and loads it if found.
         * @param $class
         * @throws \Exception
         */
        public static function loadClass($class)
        {
            $arr = explode("\\", $class);

            if ($arr[0] !== 'MinuteMedia') {
                return;
            }

            $j = count($arr);
            if ($j < 3) {
                if ($j === 2) {
                    $arr[] = $arr[1];
                } else {
                    return;
                }
            }
            $pathNoSuffix = MINUTE_MEDIA_PSR4 . '/' . implode('/', $arr);
            foreach (self::SUFFIXARRAY as $suffix) {
                $path = $pathNoSuffix . $suffix;
                if (file_exists($path)) {
                    require_once($path);
                    return;
                }
                else {
                    throw new \Exception("$path does not exist");
                }
            }
        }//end loadClass

    }
}

