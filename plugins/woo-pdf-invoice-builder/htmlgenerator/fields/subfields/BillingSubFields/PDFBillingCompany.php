<?php
namespace rnwcinv\htmlgenerator\fields\subfields\BillingSubFields;
use rnwcinv\htmlgenerator\fields\subfields\PDFSubFieldBase;

class PDFBillingCompany extends PDFSubFieldBase {
    public function GetTestFieldValue()
    {
        return "Awesome Company";
    }

    public function GetWCFieldName()
    {
        return 'billing_company';
    }
}