<?php


namespace rednaoformpdfbuilder\Utils;


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


    public static function SanitizeNumber($value,$defaultValue=0)
    {
        if($value==null||!is_numeric($value))
            return $defaultValue;

        return floatval($value);

    }

    public static function GetStringValueFromPath($value, $path,$defaultValue=null)
    {
        return Sanitizer::SanitizeString(Sanitizer::GetValueFromPath($value,$path,$defaultValue));
    }

    public static function GetBooleanValueFromPath($value, $path,$defaultValue=null)
    {
        return Sanitizer::SanitizeBoolean(Sanitizer::GetValueFromPath($value,$path,$defaultValue));
    }


    public static function IsAssoc($arr)
    {
        if (!is_array($arr)) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public static function GetValueFromPath($obj, $path, $defaultValue=null)
    {
        if(!is_array($path))
            $path=[$path];
        if($obj==null)
            return $defaultValue;

        if(is_array($obj))
            $obj=(object)$obj;

        while($currentPath=array_shift($path))
        {
            if(isset($obj->{$currentPath}))
            {
                $obj=$obj->{$currentPath};
                if(Sanitizer::IsAssoc($obj))
                    $obj=(object)$obj;
            }else
                return $defaultValue;
        }

        return $obj;
    }

}