<?php


namespace rnpdfimporter\PDFLib\utils;


class strings
{
    public static function copyStringIntoBuffer($str,$buffer,$offset)
    {
        $length=\strlen($str);
        for ($idx = 0; $idx < $length; $idx++) {
            $buffer[$offset++] = self::charCode($str[$idx]);
        }
      return $length;
    }

    public static function charCode($char)
    {
        $char = mb_substr($char, 0, 1, 'UTF-8');

        if (mb_check_encoding($char, 'UTF-8')) {
            $ret = mb_convert_encoding($char, 'UTF-32BE', 'UTF-8');
            return hexdec(bin2hex($ret));
        } else {
            return null;
        }
    }

    public static function charFromCode($code)
    {
        return \mb_convert_encoding('&#' . intval($code) . ';', 'UTF-8', 'HTML-ENTITIES');

    }

    public static function charFromHexCode($hex)
    {
        return self::charFromCode(\intval($hex,16));
    }

    public static function toHexString($num)
    {
        return strings::toHexStringOfMinLength($num,2);
    }

    public static function toHexStringOfMinLength($num,$minLength)
    {
        return \strtoupper(strings::padStart(\base_convert($num,10,16),$minLength,'0'));
    }

    public static function padStart($value,$length,$padChar)
    {
        $padding='';
        for ($idx = 0, $len = $length - \strlen($value); $idx < $len; $idx++) {
            $padding += $padChar;
        }
        return $padding . $value;
    }

    public static function toCharCode($character){
        return self::charCode($character);
    }
}