<?php
namespace rnwcinv\htmlgenerator\fields\subfields;
class PDFGrandTotalSubField extends PDFSubFieldBase {
    public function GetTestFieldValue()
    {
        return "$495.00";
    }

    public function GetWCFieldName()
    {
        return "formatted_order_total";
    }
}