<?php


namespace rnpdfimporter\PDFLib\core\integration;


class StringIntegration
{
    public static function fromCharCode($args) {
        return array_reduce($args,function($a,$b){$a.=chr($b);return $a;});
    }

    public static function padStart($string,$length, $chars = ' ')
    {
        return \str_pad($string, $length, $chars, \STR_PAD_LEFT);
    }
}