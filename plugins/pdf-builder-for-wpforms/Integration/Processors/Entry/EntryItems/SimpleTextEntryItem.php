<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/22/2019
 * Time: 5:50 AM
 */

namespace rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems;


use rednaoformpdfbuilder\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormAddressFieldSettings;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\BasicPHPFormatter;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\MultipleBoxFormatter\MultipleBoxFormatter;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\MultipleBoxFormatter\SingleBoxFormatter;
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

    public function GetHtml($style='standard',$field=null)
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


}