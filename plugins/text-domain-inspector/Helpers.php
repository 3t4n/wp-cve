<?php

namespace TextDomainInspector;

class Helpers
{
    public static function isJSON($string)
    {
        json_decode($string);

        return (json_last_error() == JSON_ERROR_NONE);
    }

    public static function isHTMLDocument($string)
    {
        return strpos($string, '<html') !== false && strpos($string, '<head>') !== false;
    }

    public static function isHTMLFragment($string)
    {
        return $string != strip_tags($string);
    }
}
