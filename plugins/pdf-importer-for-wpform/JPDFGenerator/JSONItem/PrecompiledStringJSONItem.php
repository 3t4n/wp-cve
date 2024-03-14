<?php


namespace rnpdfimporter\JPDFGenerator\JSONItem;


class PrecompiledStringJSONItem extends JSONItemBase
{
    public static function CreateFromText($generator,$parent,$text)
    {
        return new PrecompiledStringJSONItem($generator,$parent,(object)array('Text'=>$text));
    }
    public function InternalGetText()
    {
        $str= $this->GetFromData('Text','');
        $this->Length=\mb_strlen($str);
        return $str;
    }



}