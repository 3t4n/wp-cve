<?php
namespace rnwcinv\htmlgenerator\fields\subfields\BillingSubFields;
use rnwcinv\htmlgenerator\fields\subfields\PDFSubFieldBase;

class PDFBillingCity extends PDFSubFieldBase {

    public function GetTestFieldValue()
    {
        return "City";
    }

    public function GetWCFieldName()
    {
        return 'billing_city';
    }
}