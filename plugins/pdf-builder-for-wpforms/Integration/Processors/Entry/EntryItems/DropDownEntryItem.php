<?php


namespace rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems;


use rednaoformpdfbuilder\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormAddressFieldSettings;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\BasicPHPFormatter;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\MultipleBoxFormatter\SingleBoxFormatter;

class DropDownEntryItem extends MultipleSelectionEntryItem
{
    public function GetHtml($style = 'standard',$field=null)
    {
        $value=implode(', ',$this->Values);
        if($style=='similar')
        {
            /** @var WPFormAddressFieldSettings $field */
            $field = $this->Field;
            $formatter = new SingleBoxFormatter($value);

            return $formatter;
        }
        return new BasicPHPFormatter($value);
    }


}