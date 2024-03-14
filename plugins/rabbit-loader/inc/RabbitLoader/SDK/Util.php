<?php

namespace RabbitLoader\SDK;

class Util
{
    //count of headers sent so far by RabbitLoader
    private static $sentCount = 0;

    public static function getRequestMethod()
    {
        return empty($_SERVER['REQUEST_METHOD']) ? '' : strtolower($_SERVER['REQUEST_METHOD']);
    }

    public static function sendHeader($header, $replace)
    {
        if (!empty($header) && (self::$sentCount < 50) && !headers_sent()) {
            header(substr($header, 0, 150), $replace);
            self::$sentCount++;
        }
    }

    public static function append(&$body, $element)
    {
        $replaced = 0;
        $body = str_ireplace('</head>', $element . '</head>', $body, $replaced);
        if (!$replaced) {
            $body = str_ireplace('</body>', $element . '</body>', $body, $replaced);
        }
        if (!$replaced) {
            $body = str_ireplace('</html>', $element . '</html>', $body, $replaced);
        }
        return  $replaced > 0;
    }
}
