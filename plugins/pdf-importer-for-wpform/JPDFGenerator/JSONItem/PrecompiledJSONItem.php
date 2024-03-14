<?php


namespace rnpdfimporter\JPDFGenerator\JSONItem;


class PrecompiledJSONItem extends JSONItemBase
{

    public $Bits;
    public function InternalGetText()
    {
        return $this->BitsToString($this->GetFromData('Base64String',''));
    }

    public function BitsToString($bits){
        return \base64_decode($bits);

    }
}