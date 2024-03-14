<?php


namespace rnpdfimporter\JPDFGenerator\JSONItem;


class ArrayJSONItem extends JSONItemBase
{
    /** @var JSONItemBase[] */
    public $Items;
    public function __construct($generator, $parent,$data)
    {
        parent::__construct($generator, $parent,$data);
        $items=$this->GetFromData('Items',array());
        $this->Items=[];
        foreach($items as $currentItem)
            $this->Items[]=JSONFactory::GetItem($generator, $this,$currentItem);
    }


    public static function CreateWithNumbers($generator,$parent,...$numbers)
    {
        $array=new ArrayJSONItem($generator,$parent,null);
        foreach($numbers as $currentNumber)
        {
            $array->AddNumberItem($currentNumber);
        }
        return $array;

    }

    public function InternalGetText()
    {
        $str='[ ';
        foreach($this->Items as $currentItem)
            $str.=$currentItem->GetText(0).' ';

        $str.=']';
        $this->Length=\mb_strlen($str);
        return $str;
    }

    public function AddNumberItem(...$numbers){
        foreach($numbers as $currentNumber)
        {
            $item=RawStringJSONItem::CreateFromText($this->Generator,$this,$currentNumber);
            $item->GetText(0);
            $this->Items[]=$item;
        }

    }
}