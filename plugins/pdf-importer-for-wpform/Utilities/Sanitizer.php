<?php

namespace rnpdfimporter\Utilities;


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

    public static function SanitizeBoolean($value,$defaultValue=false)
    {
        if($value==null)
            return $defaultValue;

        return $value==true;

    }

    public static function GetStringValueFromPath($value, $path,$defaultValue=null)
    {
        return Sanitizer::SanitizeString(Sanitizer::GetValueFromPath($value,$path,$defaultValue));
    }

    public static function GetNumberValueFromPath($value, $path,$defaultValue=null)
    {
        return Sanitizer::SanitizeNumber(Sanitizer::GetValueFromPath($value,$path,$defaultValue));
    }

    public static function GetValueFromPath($obj, $path, $defaultValue=null)
    {
        if(!is_array($path))
            $path=[$path];
        if($obj==null)
            return null;

        while(($currentPath=array_shift($path))!==null)
        {
            if(is_array($obj))
            {
                if(isset($obj[$currentPath]))
                    $obj=$obj[$currentPath];
                else
                    return $defaultValue;
            }else{
                if(isset($obj->{$currentPath}))
                    $obj=$obj->{$currentPath};
                else
                    return $defaultValue;
            }
        }

        return $obj;
    }

}