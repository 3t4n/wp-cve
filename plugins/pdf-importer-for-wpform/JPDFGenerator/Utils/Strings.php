<?php


namespace rnpdfimporter\JPDFGenerator\Utils;


class Strings
{
    public static function BitsToString($bits)
    {
        $str='';
        foreach ($bits as $currentBit)
        {
            $str.=chr($currentBit);
        }

        return $str;
    }
}