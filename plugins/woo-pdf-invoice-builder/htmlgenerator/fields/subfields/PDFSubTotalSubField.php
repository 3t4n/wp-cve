<?php
namespace rnwcinv\htmlgenerator\fields\subfields;
class PDFSubTotalSubField extends PDFSubFieldBase {
    public function GetTestFieldValue()
    {
        return "$435.00";
    }

    public function GetWCFieldName()
    {
        return "";
    }

    public function GetRealFieldValue($format='')
    {
        return $this->orderValueRetriever->GetTotal('cart_subtotal');
    }


}