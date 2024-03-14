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
use stdClass;

class SimpleTextEntryItem extends EntryItemBase
{
    public $Value;
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


    public function GetText()
    {
        return $this->Value;
    }
}