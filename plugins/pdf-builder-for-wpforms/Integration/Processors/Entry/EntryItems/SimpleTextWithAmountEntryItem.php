<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/22/2019
 * Time: 5:50 AM
 */

namespace rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems;


use rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters\BasicPHPFormatter;
use stdClass;

class SimpleTextWithAmountEntryItem extends EntryItemBase
{
    public $Value;
    public $Amount;
    public $Quantity;
    public function SetValue($value,$amount,$quantity=null)
    {
        $this->Value=$value;
        $this->Amount=$amount;
        $this->Quantity=$quantity;
        return $this;
    }


    protected function InternalGetObjectToSave()
    {
        return (object)array(
            'Value'=>$this->Value,
            'Amount'=>$this->Amount,
            'Quantity'=>$this->Quantity
        );
    }

    public function InitializeWithOptions($field,$options)
    {
        $this->Field=$field;
        if(isset($options->Value))
            $this->Value=$options->Value;
        if(isset($options->Amount))
            $this->Amount=$options->Amount;

        if(isset($options->Quantity))
            $this->Quantity=$options->Quantity;
    }

    public function GetHtml($style='standard',$field=null)
    {
        return new BasicPHPFormatter($this->Value);
    }


}