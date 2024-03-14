<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/22/2019
 * Time: 5:56 AM
 */

namespace rnpdfimporter\core\Integration\Processors\Entry\EntryItems;


use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\BasicPHPFormatter;
use stdClass;

class MultipleSelectionEntryItem extends EntryItemBase
{
    public $Values=[];
    /** @var MultipleSelectionValueItem[] */
    public $Items=[];




    public function SetValue($value,$amount=0)
    {
        $this->Values=[];
        if(\is_array($value))
        {
            $this->Values =[];
            foreach($value as $currentValue)
            {

                $currentValue=$this->GetLabel($currentValue);
                if($currentValue=='')
                    continue;
                $this->Values[]=$currentValue;
                $this->Items[]=(new MultipleSelectionValueItem())->InitializeWithValues($currentValue,$amount);
            }
        }
        else
        {
            $value=$this->GetLabel($value);
            if($value !='')
            {
                $this->Values[] = $value;
                $this->Items[] = (new MultipleSelectionValueItem())->InitializeWithValues($value, $amount);
            }
        }

        return $this;
    }

    public function GetLabel($value)
    {
        if(isset($this->Field->Items))
        {
            foreach($this->Field->Items as $item)
            {
                if($value!=''&&$item->Value==$value)
                    return $item->Label;

                if($value!=''&&$item->Label==$value)
                    return $item->Label;
            }
        }

        return $value;

    }

    public function AddItem($value,$amount)
    {
        $value=$this->GetLabel($value);
        $this->Items[]=(new MultipleSelectionValueItem())->InitializeWithValues($value,$amount);
        if($this->Values==null)
            $this->Values=[];
        $this->Values[]=$value;

    }
    public function GetAmount(){
        if(count($this->Items)==0)
            return 0;
        return $this->Items[0]->Amount;
    }

    protected function InternalGetObjectToSave()
    {
        $value='';
        if(\count($this->Values)>0)
            $value=\implode('@;;@',$this->Values);
        return (object)Array(
            'Value'=>$value,
            'Values'=>$this->Values,
            'Items'=>$this->Items
        );
    }


    public function InitializeWithOptions($field,$options)
    {
        $this->Field=$field;
        $this->Values=[];

        if(isset($options->Items))
        {
            foreach($options->Items as $CurrentItem)
            {
                $item=(new MultipleSelectionValueItem())->InitializeWithOptions($CurrentItem);
                $this->Items[]=$item;
                $this->Values[]=$item->Value;
            }


            return;
        }


        if(isset($options->Values))
            if(\is_array($options->Values))
                $this->Values=$options->Values;
            else
                $this->Values[]=$options->Values;


    }

    public function Contains($value)
    {
        if(!\is_array($value))
            $value=[$value];

        foreach($value as $currentValue)
            if( \in_array($currentValue,$this->Values))
                return true;
        return false;
    }


    public function GetText()
    {
        return \implode(', ',$this->Values);

    }

    public function GetHtml($style='standard')
    {
        return new BasicPHPFormatter(implode(', ',$this->Values));
    }
}

class MultipleSelectionValueItem{
    public $Value='';
    public $Amount=0;

    public function InitializeWithValues($value,$amount)
    {
        $this->Value=$value;
        $this->Amount=$amount;
        return $this;

    }

    public function InitializeWithOptions($CurrentItem)
    {
        if(isset($CurrentItem->Value))
            $this->Value=$CurrentItem->Value;
        if(isset($CurrentItem->Amount))
            $this->Amount=\floatval($CurrentItem->Amount);
        return $this;
    }


}