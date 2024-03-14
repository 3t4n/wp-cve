<?php


namespace rnpdfimporter\core\Utils;


use Exception;

class JSONSanitizer
{
    const PROPERTY_INTEGER=1;
    const PROPERTY_STRING=2;
    const PROPERTY_EMAIL=3;
    const FILE_NAME=4;
    const PROPERTY_ARRAY=5;
    public static function Sanitize($jsonObject,$propertiesDictionary,$throwErrorWhenPropertyDoesNotExist=false)
    {
        self::SanitizeSection($jsonObject,$propertiesDictionary,$throwErrorWhenPropertyDoesNotExist);
        return $jsonObject;

    }


    private static function SanitizeSection($jsonObject,$propertiesDictionary,$throwErrorWhenPropertyDoesNotExist)
    {
        if($jsonObject==null)
            return null;

        if($propertiesDictionary==null)
            return null;


        foreach($propertiesDictionary as $Name=>$value)
        {
            if(!isset($jsonObject->$Name))
            {
                if($throwErrorWhenPropertyDoesNotExist)
                    throw new Exception('Property '.$Name.' does not exist');

                continue;
            }

            if(\is_array($value))
            {
                self::SanitizeSection($jsonObject->$Name, $value, $throwErrorWhenPropertyDoesNotExist);
                continue;
            }


            $valueToSanitize=null;
            if(\is_array($jsonObject->$Name))
                $valueToSanitize=$jsonObject->$Name;
            else
                $valueToSanitize=[$jsonObject->$Name];

            foreach($valueToSanitize as &$currentValue)
            {
                switch($value)
                {
                    case self::PROPERTY_INTEGER:
                        $currentValue=\intval($currentValue);
                        break;
                    case self::PROPERTY_STRING:
                        if(!\is_string($currentValue))
                            $currentValue='';
                        $currentValue=\stripslashes(\strval($currentValue));
                        break;
                    case self::PROPERTY_EMAIL:
                        $currentValue=\sanitize_email($currentValue);
                        break;
                    case self::FILE_NAME:
                        $currentValue=\sanitize_file_name($currentValue);
                        break;
                    case self::PROPERTY_ARRAY:
                        if(!is_array($currentValue))
                            $currentValue=[$currentValue];
                }
            }

            unset($propertyToSanitize);


        }

    }

}