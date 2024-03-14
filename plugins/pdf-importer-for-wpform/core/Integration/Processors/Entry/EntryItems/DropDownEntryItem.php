<?php


namespace rnpdfimporter\core\Integration\Processors\Entry\EntryItems;


use rnpdfimporter\core\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormAddressFieldSettings;
use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\BasicPHPFormatter;
use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\MultipleBoxFormatter\SingleBoxFormatter;

class DropDownEntryItem extends MultipleSelectionEntryItem
{
    public function GetHtml($style = 'standard')
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