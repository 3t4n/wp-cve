<?php
namespace rnwcinv\htmlgenerator\fields\subfields\ShippingSubFields;
use rnwcinv\htmlgenerator\fields\subfields\PDFSubFieldBase;

class PDFShippingZip extends PDFSubFieldBase {
    public function GetTestFieldValue()
    {
        return "Zip";
    }

    public function GetWCFieldName()
    {
        return "shipping_postcode";
    }
}