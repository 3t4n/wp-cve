<?php


namespace rnpdfimporter\JPDFGenerator\JSONItem;


class RawStringJSONItem extends JSONItemBase
{
    public static function CreateFromText($generator,$parent,$text)
    {
        return new RawStringJSONItem($generator,$parent,(object)array('Text'=>$text));
    }

    public function InternalGetText()
    {
        $text= $this->GetFromData('Text');
        $str=$text;
        return $str;
    }
}