<?php


namespace rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems;


use rednaoformpdfbuilder\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormAddressFieldSettings;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\BasicPHPFormatter;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\MultipleBoxFormatter\SingleBoxFormatter;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\MultipleLineFormatter;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\MultipleOptionsFormatter\MultipleOptionsFormatter;
use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\MultipleOptionsFormatter\MultipleOptionsFormatterType;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\MultipleOptionsFieldSettings;

class LikertEntryItem extends MultipleSelectionEntryItem
{
    public $Rows=[];
    public function GetHtml($style = 'standard',$field=null)
    {
        $multipleLineFormatter=new MultipleLineFormatter();
        foreach($this->Rows as $key=>$value)
        {
            if($key!='')
                $multipleLineFormatter->AddLine($key.':'.implode(', ',$value));
            else
                $multipleLineFormatter->AddLine(implode(', ',$value));
        }

        return $multipleLineFormatter;

    }


    public function AddRow($row,$value)
    {
        if(!isset($this->Rows[$row]))
            $this->Rows[$row]=[];
        $this->Rows[$row][]=$value;
    }


}