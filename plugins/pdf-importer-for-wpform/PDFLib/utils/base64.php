<?php


namespace rnpdfimporter\PDFLib\utils;


class base64
{
    const DATA_URI_PREFIX_REGEX = '/^(data)?:?([\w\/\+]+)?;?(charset=[\w-]+|base64)?.*,/i';
    public static function decodeFromBase64DataUri($dataUri){
        $trimmedUri=trim($dataUri);
        $prefix=\substr($trimmedUri,0,100);

        $matches=null;
        $res=\preg_match(self::DATA_URI_PREFIX_REGEX,$prefix,$matches);

    }
}