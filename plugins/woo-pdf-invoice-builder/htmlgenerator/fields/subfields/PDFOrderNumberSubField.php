<?php
namespace rnwcinv\htmlgenerator\fields\subfields;
class PDFOrderNumberSubField extends PDFSubFieldBase {


    public function GetTestFieldValue()
    {
        return "1";
    }


    public function GetWCFieldName()
    {
        return 'order_number';
    }

    public function FormatValue($value,$format='')
    {
        $prefix=$this->GetFieldOptions('prefix');
        $sufix=$this->GetFieldOptions('sufix');
        $digits=$this->GetFieldOptions('digits');


        if(is_numeric($value)&&$digits>0)
            $value=str_pad(intval($value),$digits,'0',STR_PAD_LEFT);

        return $prefix.$value.$sufix;
    }

}