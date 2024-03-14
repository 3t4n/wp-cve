<?php


namespace rnpdfimporter\core\Integration\Processors\Entry\EntryItems;


use rnpdfimporter\core\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormAddressFieldSettings;
use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\BasicPHPFormatter;
use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\MultipleBoxFormatter\SingleBoxFormatter;
use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\MultipleOptionsFormatter\MultipleOptionsFormatter;
use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\MultipleOptionsFormatter\MultipleOptionsFormatterType;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\MultipleOptionsFieldSettings;

class CheckBoxEntryItem extends MultipleSelectionEntryItem
{
    public function GetHtml($style = 'standard')
    {
        $value=implode(', ',$this->Values);
        /** @var MultipleOptionsFieldSettings $field */
        $field=$this->Field;
        if($style=='similar')
        {
            $formatter=new MultipleOptionsFormatter(MultipleOptionsFormatterType::$Checkbox);

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