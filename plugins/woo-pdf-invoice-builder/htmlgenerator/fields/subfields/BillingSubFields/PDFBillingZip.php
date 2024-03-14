<?php
namespace rnwcinv\htmlgenerator\fields\subfields\BillingSubFields;
use rnwcinv\htmlgenerator\fields\subfields\PDFSubFieldBase;

class PDFBillingZip extends PDFSubFieldBase {
    public function GetTestFieldValue()
    {
        return "Zip";
    }

    public function GetWCFieldName()
    {
        return "billing_postcode";
    }
}