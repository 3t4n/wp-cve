<?php


namespace rnwcinv\utilities;


class ArrayUtils
{
    public static function Some($array,$fn)
    {
        foreach ($array as $value) {
            if($fn($value)) {
                return true;
            }
        }
        return false;
    }

    public static function GetValueOrDefault($array,$path,$default='')
    {
        if(!\is_array($path))
            $path=[$path];

        $currentPath='';
        $currentValue=$array;
        while(($currentPath=\array_shift($path))!=null)
        {
            if(!\array_key_exists($currentPath,$currentValue))
                return $default;

            $currentValue=$currentValue[$currentPath];
        }

        return $currentValue;
    }


    public static function Find($array,$fn)
    {
        foreach ($array as $value) {
            if($fn($value)) {
                return $value;
            }
        }
        return null;

    }

    public static function Filter($array,$fn)
    {
        $items=array();
        foreach ($array as $value) {
            if($fn($value)) {
                $items[]= $value;
            }
        }
        return $items;

    }

    public static function Map($array,$fn)
    {
        $arrayToReturn=array();
        foreach ($array as $currentItem)
        {
            $arrayToReturn[]=$fn($currentItem);
        }

        return $arrayToReturn;
    }

}