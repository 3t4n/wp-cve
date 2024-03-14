<?php


namespace rnpdfimporter\JPDFGenerator\JSONItem;


use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\FileUploadEntryItem;

class DictionaryObjectItem extends JSONItemBase
{
    /** @var DictionaryItem[] */
    public $Dictionary;
    public function __construct($generator, $parent,$data)
    {
        parent::__construct($generator, $parent,$data);
        $this->Dictionary=array();

        $dict=$this->GetFromData('Dict',array());
        foreach($dict as $currentItem)
            $this->Dictionary[]=new DictionaryItem($currentItem->Key,JSONFactory::GetItem($generator, $this,$currentItem->Value));


    }


    public function SetValue($key,$value)
    {
        foreach($this->Dictionary as $item)
        {
            if($item->Key==$key)
            {
                $item->Value = $value;
                return;
            }
        }

        $this->Dictionary[]=new DictionaryItem($key,$value);
    }

    public function GetParent(){
        $parentRef= $this->GetValue('/Parent');
        if($parentRef==null)
            return null;

        if($this->Generator==null)
            return null;

        return $this->Generator->GetObjectByTag($parentRef->GetText());

    }

    public function GetValue($key)
    {
        foreach($this->Dictionary as $item)
        {
            if($item->Key==$key)
            {
                return $item->Value;
            }
        }

        if($key!='/Parent')
        {
            $parent = $this->GetParent();
            if ($parent != null && $parent->MaybeGetDictionary() != null)
                return $parent->MaybeGetDictionary()->GetValue($key);
        }

        return null;
    }

    public function RemoveKey($key)
    {
        for($i=0;$i<count($this->Dictionary);$i++)
        {
            if($this->Dictionary[$i]->Key==$key)
            {
                unset($this->Dictionary[$i]);
                $this->Dictionary=\array_values($this->Dictionary);
                return true;
            }
        }

        return false;
    }

    public function GetNumberValue($path,$defaultValue=null)
    {
        $value=$this->GetValue($path);
        if($value==null)
            return $defaultValue;

        return floatval($value->GetText(0));

    }



    public function InternalGetText()
    {
        $str="<<\n";

        foreach($this->Dictionary as $dictionaryItem)
        {

            $str.=$dictionaryItem->Key.' '.$dictionaryItem->Value->GetText(0)."\n";
        }

        $str.='>>';
        $this->Length=\mb_strlen($str);
        return $str;
    }
}

class DictionaryItem{
    public $Key;
    /** @var JSONItemBase */
    public $Value;

    public function __construct($key,$value)
    {
        $this->Key=$key;
        $this->Value=$value;
    }


}