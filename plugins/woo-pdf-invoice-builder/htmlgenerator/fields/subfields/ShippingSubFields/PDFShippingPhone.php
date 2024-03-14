<?php
namespace rnwcinv\htmlgenerator\fields\subfields\ShippingSubFields;
use rnwcinv\htmlgenerator\fields\subfields\PDFSubFieldBase;

class PDFShippingPhone extends PDFSubFieldBase {
    public function GetTestFieldValue()
    {
        return "(555)555-555";
    }

    public function GetWCFieldName()
    {
        return 'shipping_phone';
    }
}