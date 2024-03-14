<?php


namespace rnpdfimporter\JPDFGenerator\JSONItem;


class PrecompiledRawJSONItem extends JSONItemBase
{

    public function InternalGetText()
    {
        $str= $this->GetFromData('Text','');
        $this->Length=\mb_strlen($str);
        return $str;
    }

}