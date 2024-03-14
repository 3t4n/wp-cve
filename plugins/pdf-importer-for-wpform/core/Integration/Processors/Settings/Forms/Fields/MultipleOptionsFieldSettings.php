<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/21/2019
 * Time: 4:59 AM
 */

namespace rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields;
class MultipleOptionsFieldSettings extends FieldSettingsBase
{
    /** @var MultipleOptionsItem */
    public $Items;

    public function __construct()
    {
        $this->Items=[];
        $this->UseInConditions=true;
    }


    public function GetType()
    {
        return "Multiple";
    }

    public function AddOption($label,$value,$price=''){
        $this->Items[]=new MultipleOptionsItem($label,$value,$price);
    }

    public function InitializeFromOptions($options)
    {
        parent::InitializeFromOptions($options);
        foreach($options->Items as $Item)
        {
            $price='';
            if(isset($Item->Price))
                $price=$Item->Price;
            $this->Items[]=new MultipleOptionsItem($Item->Label,$Item->Value,$price);
        }
    }
}


class MultipleOptionsItem{
    public $Label;
    public $Value;
    public $Price;

    public function __construct($label,$value,$price='')
    {
        $this->Label=$label;
        $this->Value=$value;
        $this->Price=$price;
    }


}