<?php


namespace rnwcinv\utilities;


class Sanitizer
{
    public static function SanitizeString($value)
    {
        if($value==null)
            return '';

        if(is_array($value))
            return '';

        return strval($value);
    }

    public static function GetStringValueFromPath($value, $path,$defaultValue=null)
    {
        return Sanitizer::SanitizeString(Sanitizer::GetValueFromPath($value,$path,$defaultValue));
    }

    public static function GetValueFromPath($obj, $path, $defaultValue=null)
    {
        if(!is_array($path))
            $path=[$path];
        if($obj==null)
            return null;

        if(is_array($obj))
            $obj=(object)$obj;

        while($currentPath=array_shift($path))
        {
            if(isset($obj->{$currentPath}))
            {
                $obj=$obj->{$currentPath};
                if(is_array($obj))
                    $obj=(object)$obj;
            }else
                return $defaultValue;
        }

        return $obj;
    }

    public static function SanitizeNumber($value,$defaultValue=0)
    {
        if($value==null||!is_numeric($value))
            return $defaultValue;

        return floatval($value);

    }

}