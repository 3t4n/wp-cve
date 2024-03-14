<?php
namespace rnwcinv\htmlgenerator\fields\subfields\ShippingSubFields;
use rnwcinv\htmlgenerator\fields\subfields\PDFSubFieldBase;

class PDFShippingEmail extends PDFSubFieldBase {
    public function GetTestFieldValue()
    {
        return "Customer@email.com";
    }

    public function GetWCFieldName()
    {
        return "shipping_email";
    }
}