<?php


namespace rnpdfimporter\JPDFGenerator\Utils;


class ObjectUtils
{
    public static function GetValue($object,$path,$defaultValue=null)
    {
        $path=explode('/',$path);
        $subPath='';
        if(!is_array($path))
            $path=[$path];

        while($subPath=array_shift($path))
        {
            if(!isset($object->$subPath))
                return $defaultValue;

            $object=$object->$subPath;
        }

        return $object;
    }
}