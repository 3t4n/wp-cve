<?php

namespace rnwcinv\htmlgenerator\fields\subfields;

class PDFShippingMethod extends PDFSubFieldBase {
   /* public function __toString()
    {
        $date=null;
        $date=$this->GetFieldValue();
        $format=$this->GetStringValue('format');
        $formattedDate=date($format,$date);
        if(!$formattedDate)
            return 'Invalid Format';
        return $formattedDate;
    }*/



    public function GetTestFieldValue()
    {
        return 'Standard';
    }

    public function GetWCFieldName()
    {
        return "shipping_method";
    }




}