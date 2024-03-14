<?php
class PDFOrderNumber extends PDFConverterBase {
    public function __toString()
    {
        $prefix=$this->GetStringValue('prefix');
        $sufix=$this->GetStringValue('sufix');
        $digits=$this->GetNumericValue('digits');

        $number=$this->GetFieldValue();
        if(is_numeric($number)&&$digits>0)
            $number=str_pad(intval($number),$digits,'0',STR_PAD_LEFT);

        return $prefix.$number.$sufix;
    }

    public function GetTestFieldValue()
    {
        return "1";
    }


    public function GetWCFieldName()
    {
        return 'order_number';
    }
}