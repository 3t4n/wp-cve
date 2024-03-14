<?php


namespace rnpdfimporter\PDFLib\core\integration;


class ObjectIntegration
{
    public static function Coalesce($object,$defaultValue)
    {
        if($object==null)
            return $defaultValue;

        return $object;
    }

    public static function ExtractPropertyFromObject($object,$propertyName)
    {
        if(!isset($object[$propertyName]))
            return null;
        return $object[$propertyName];
    }

    public static function FirstNonEmpty(...$obj)
    {
        foreach($obj as $value)
        {
            if ($value != null)
                return $value;
        }
        return null;
    }

}