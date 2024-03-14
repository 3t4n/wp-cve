<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/22/2019
 * Time: 5:50 AM
 */

namespace rnpdfimporter\core\Integration\Processors\Entry\EntryItems;


use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\BasicPHPFormatter;
use stdClass;

class SimpleTextWithAmountEntryItem extends EntryItemBase
{
    public $Value;
    public $Amount;
    public function SetValue($value,$amount)
    {
        $this->Value=$value;
        $this->Amount=$amount;
        return $this;
    }


    protected function InternalGetObjectToSave()
    {
        return (object)array(
            'Value'=>$this->Value,
            'Amount'=>$this->Amount
        );
    }

    public function InitializeWithOptions($field,$options)
    {
        $this->Field=$field;
        if(isset($options->Value))
            $this->Value=$options->Value;
        if(isset($options->Amount))
            $this->Amount=$options->Amount;
    }

    public function GetHtml($style='standard')
    {
        return new BasicPHPFormatter($this->Value);
    }


    public function GetText()
    {
        return $this->Value;
    }

    public function GetPrice(){
        return $this->Amount;
    }

}