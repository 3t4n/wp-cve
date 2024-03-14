<?php
namespace rnwcinv\htmlgenerator\fields\subfields;
class PDFDiscountTotalSubField extends PDFSubFieldBase {
    public function GetTestFieldValue()
    {
        return "0.00";
    }

    public function GetWCFieldName()
    {
        return "";
    }

    public function GetRealFieldValue($format='')
    {
        return $this->orderValueRetriever->GetTotal('discount');

    }


}