<?php
namespace rnwcinv\htmlgenerator\fields\subfields\BillingSubFields;
use rnwcinv\htmlgenerator\fields\subfields\PDFSubFieldBase;

class PDFBillingEmail extends PDFSubFieldBase {
    public function GetTestFieldValue()
    {
        return "Customer@email.com";
    }

    public function GetWCFieldName()
    {
        return "billing_email";
    }
}