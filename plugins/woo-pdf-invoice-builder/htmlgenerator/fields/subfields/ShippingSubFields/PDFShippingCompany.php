<?php
namespace rnwcinv\htmlgenerator\fields\subfields\ShippingSubFields;
use rnwcinv\htmlgenerator\fields\subfields\PDFSubFieldBase;

class PDFShippingCompany extends PDFSubFieldBase {
    public function GetTestFieldValue()
    {
        return "Awesome Company";
    }

    public function GetWCFieldName()
    {
        return 'shipping_company';
    }
}