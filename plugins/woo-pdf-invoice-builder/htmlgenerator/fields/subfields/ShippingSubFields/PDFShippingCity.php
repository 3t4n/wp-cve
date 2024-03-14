<?php
namespace rnwcinv\htmlgenerator\fields\subfields\ShippingSubFields;
use rnwcinv\htmlgenerator\fields\subfields\PDFSubFieldBase;

class PDFShippingCity extends PDFSubFieldBase {

    public function GetTestFieldValue()
    {
        return "City";
    }

    public function GetWCFieldName()
    {
        return 'shipping_city';
    }
}