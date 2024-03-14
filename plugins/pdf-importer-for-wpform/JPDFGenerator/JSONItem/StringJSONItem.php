<?php


namespace rnpdfimporter\JPDFGenerator\JSONItem;


class StringJSONItem extends JSONItemBase
{
    public static function CreateFromText($generator,$parent,$text)
    {
        return new StringJSONItem($generator,$parent,(object)array('Text'=>$text));
    }

    public function InternalGetText()
    {
        $text= $this->GetFromData('Text');
        $str='('. mb_convert_encoding('þÿ','8bit').  mb_convert_encoding($text,'UTF-16').')';
        return $str;
    }
}