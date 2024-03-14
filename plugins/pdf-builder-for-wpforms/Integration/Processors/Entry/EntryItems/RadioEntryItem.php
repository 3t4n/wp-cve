<?php


namespace rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems;


use rednaoformpdfbuilder\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormAddressFieldSettings;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\BasicPHPFormatter;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\MultipleBoxFormatter\SingleBoxFormatter;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\MultipleOptionsFormatter\MultipleOptionsFormatter;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\MultipleOptionsFormatter\MultipleOptionsFormatterType;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\MultipleOptionsFieldSettings;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\MultipleOptionsItem;

class RadioEntryItem extends MultipleSelectionEntryItem
{
    public function GetHtml($style = 'standard',$field=null)
    {
        $value=implode(', ',$this->Values);
        /** @var MultipleOptionsFieldSettings $field */
        $field=$this->Field;
        if($style=='similar')
        {
            $formatter=new MultipleOptionsFormatter(MultipleOptionsFormatterType::$Radio);

            foreach($field->Items as $currentItem)
            {
                $isSelected=false;
                foreach($this->Values as $currentValue)
                    if($currentValue==$currentItem->Label)
                        $isSelected=true;

                $formatter->AddOption($currentItem->Label,$isSelected);
            }

            foreach($this->Values as $value)
                $formatter->AddOption($value,true);

            return $formatter;
        }
        return new BasicPHPFormatter($value);
    }


}