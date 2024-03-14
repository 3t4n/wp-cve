<?php


namespace rnpdfimporter\JPDFGenerator\JSONItem\Streams;


class RawJSONItem extends StreamJSONItemBase
{

    public function StreamToText()
    {
        return $this->BitsToString($this->GetFromData('Base64String',''));
    }

    public function BitsToString($bits){
        return \base64_decode($bits);
    }
}