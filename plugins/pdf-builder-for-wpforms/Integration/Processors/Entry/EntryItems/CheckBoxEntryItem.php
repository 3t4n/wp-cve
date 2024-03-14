<?php


namespace rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems;


use rednaoformpdfbuilder\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormAddressFieldSettings;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\BasicPHPFormatter;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\MultipleBoxFormatter\SingleBoxFormatter;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\MultipleLineFormatter;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\MultipleOptionsFormatter\MultipleOptionsFormatter;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\MultipleOptionsFormatter\MultipleOptionsFormatterType;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\MultipleOptionsFieldSettings;

class CheckBoxEntryItem extends MultipleSelectionEntryItem
{
    public function GetHtml($style = 'standard',$templateField=null)
    {
        $multipleLineFormatter=new MultipleLineFormatter($templateField);

        if($this->Field->SubType=='survey_likert')
        {
            $found=false;
            foreach($this->Values as $currentValue)
            {
                foreach($this->Field->Items as $currentItem)
                {
                    if($currentItem->Value==$currentValue)
                    {
                        $multipleLineFormatter->AddLine($currentItem->Label);
                    }
                }
                if(!$found)
                    continue;
                $multipleLineFormatter->AddLine($currentValue);
            }

        }else
            foreach($this->Values as $currentValue)
                $multipleLineFormatter->AddLine($currentValue);

        /** @var MultipleOptionsFieldSettings $field */
        $field=$this->Field;
        if($style=='similar')
        {
            $formatter=new MultipleOptionsFormatter(MultipleOptionsFormatterType::$Checkbox,$templateField);

            foreach($field->Items as $currentItem)
            {
                $isSelected=false;
                foreach($this->Values as $currentValue)
                    if($currentValue==$currentItem->Label)
                        $isSelected=true;

                $formatter->AddOption($currentItem->Label,$isSelected);
            }


            return $formatter;
        }
        if($this->Field->SubType=='likert_scale'||$this->Field->SubType=='survey_likert')
            $multipleLineFormatter->SetSingleLine();
        return $multipleLineFormatter;
    }


}