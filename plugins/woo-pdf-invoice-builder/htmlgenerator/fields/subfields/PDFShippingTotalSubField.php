<?php
namespace rnwcinv\htmlgenerator\fields\subfields;
class PDFShippingTotalSubField extends PDFSubFieldBase {
    public function GetTestFieldValue()
    {
        return "$15.00";
    }

    public function GetWCFieldName()
    {
        return "";
    }

    public function GetRealFieldValue($format='')
    {
        return $this->orderValueRetriever->GetTotal('shipping');
    }
}