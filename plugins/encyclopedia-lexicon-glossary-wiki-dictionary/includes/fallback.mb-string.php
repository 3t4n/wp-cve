<?php

if (!Function_Exists('MB_Convert_Encoding')) {
    function MB_Convert_Encoding($str, $to_encoding, $from_encoding = null)
    {
        if ($from_encoding == 'UTF-8' && $to_encoding == 'HTML-ENTITIES') {
            return HTMLSpecialChars_Decode(UTF8_Decode(HTMLEntities($str, ENT_QUOTES, 'utf-8', false)));
        } else {
            return @IConv($from_encoding, $to_encoding, $str);
        }
    }
}

if (!Function_Exists('MB_StrPos')) {
    function MB_StrPos($haystack, $needle, $offset = 0)
    {
        return StrPos($haystack, $needle, $offset);
    }
}

if (!Function_Exists('MB_SubStr')) {
    function MB_SubStr($string, $start, $length = null)
    {
        return SubStr($string, $start, $length);
    }
}

if (!Function_Exists('MB_StrLen')) {
    function MB_StrLen($string)
    {
        return StrLen($string);
    }
}

if (!Function_Exists('MB_StrToUpper')) {
    function MB_StrToUpper($string)
    {
        return StrToUpper($string);
    }
}
