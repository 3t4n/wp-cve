<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/21/2019
 * Time: 4:25 AM
 */

namespace rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields;

abstract class FieldSettingsBase
{
    public $Id;
    public $Label;
    public $Type;
    public $SubType;
    public $UseInConditions=false;
    public function Initialize($FieldId,$Label,$SubType){
        $this->Id=$FieldId;
        $this->Label=$Label;
        $this->Type=$this->GetType();
        $this->SubType=$SubType;
        return $this;
    }


    public function GetValue($options,$path=[],$defaultValue=null)
    {
        $currentValue=$options;
        if(!\is_array($path))
            $path=[$path];

        while(($value=\array_shift($path))!=null)
        {
            if(!isset($options->$value))
                return $defaultValue;

            $currentValue=$options->$value;
        }

        return $currentValue;
    }

    public function GetStringValue($options,$path=[],$defaultValue='')
    {
        $value=$this->GetValue($options,$path,$defaultValue);

        if(!\is_string($value))
            return $defaultValue;

        $value=\strval($value);

        if(trim($value)=='')
            return $defaultValue;

        return $value;
    }

    public function GetBoolValue($options,$path=[],$defaultValue=false)
    {
        $value=$this->GetValue($options,$path,$defaultValue);
        if($value==false)
            return false;

        return true;
    }

    public function InitializeFromOptions($options)
    {
        $this->Id=$options->Id;
        $this->Label=$options->Label;
        $this->Type=$options->Type;
        $this->SubType=$options->SubType;
    }

    public function SetStringProperty($propertyName,$options,$path,$defaultValue='')
    {
        $this->$propertyName=$this->GetStringValue($options,$path,$defaultValue);

    }

    public abstract function GetType();
}