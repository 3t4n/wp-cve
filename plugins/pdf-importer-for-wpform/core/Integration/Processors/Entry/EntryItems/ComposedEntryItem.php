<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/22/2019
 * Time: 5:50 AM
 */

namespace rnpdfimporter\core\Integration\Processors\Entry\EntryItems;


use rnpdfimporter\core\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormAddressFieldSettings;
use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\BasicPHPFormatter;
use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\MultipleBoxFormatter\MultipleBoxFormatter;
use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\MultipleBoxFormatter\SingleBoxFormatter;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\ComposedFieldSettings;
use rnpdfimporter\core\Utils\ArrayUtils;
use rnpdfimporter\Utilities\Sanitizer;
use stdClass;

class ComposedEntryItem extends EntryItemBase
{
    public $Value;
    /** @var ComposedFieldSettings */
    public $Field;
    public function SetValue($value)
    {
        $this->Value=$value;
        return $this;
    }


    protected function InternalGetObjectToSave()
    {
        return (object)array(
            'Value'=>$this->Value
        );
    }

    public function InitializeWithOptions($field,$options)
    {
        $this->Field=$field;
        if(isset($options->Value))
            $this->Value=$options->Value;
    }

    public function InitializeWithString($field,$stringValue)
    {
        $this->Field=null;
        $this->Value=$stringValue;

    }

    public function GetHtml($style='standard')
    {
        if($style=='similar')
        {
            /** @var WPFormAddressFieldSettings $field */
            $field = $this->Field;
            $formatter = new SingleBoxFormatter($this->Value);

            return $formatter;
        }
        return new BasicPHPFormatter($this->Value);
    }

    public function GetSection($sectionId){
        if(isset($this->Value->$sectionId))
            return $this->Value->$sectionId;

        foreach($this->Field->Items as $currentItem)
        {
            if($currentItem->Id==$sectionId)
            {
                return Sanitizer::GetStringValueFromPath($this->Value,$currentItem->Path);
            }

        }


        return '';

    }
    public function GetText()
    {
        $text='';
        foreach($this->Field->Items as $currentItem)
        {
            $text=$this->AddItem($currentItem,$this->Value,$text);

        }
        return $text;
    }


    private function AddItem($currentItem, $value,$text) {
        $currentPath='';
        foreach($currentItem->Path as $currentPath )
        {
            if(!isset($value->$currentPath))
            {
                $value='';
                break;
            }

            $value=$value->$currentPath;
        }

        if(\is_object($value)||\is_array($value))
            $value='';
        $value=\strval($value);

        if($value=='')
            return $text;

        if($text!='')
        {
            if($currentItem->AddCommaBefore)
                $text.=', ';
            else
                $text.=' ';
        }

        $text.=$value;
        return $text;

    }

    public function GetItemValue($itemId)
    {
        $value=$this->Value;
        foreach($this->Field->Items as $currentItem)
        {
            if($currentItem->Id==$itemId)
            {
                foreach($currentItem->Path as $currentPath )
                {
                    if(!isset($value->$currentPath))
                    {
                        return null;
                    }

                    $value=$value->$currentPath;
                }

                return $value;
            }

        }

        return null;
    }

    public function ProcessFieldMethod($methodName)
    {
        $value=$this->GetItemValue($methodName);
        if($value==null)
            return '';

        return $value;
    }


}