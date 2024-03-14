<?php
namespace rnwcinv\htmlgenerator\fields\subfields\BillingSubFields;
use rnwcinv\htmlgenerator\fields\subfields\PDFSubFieldBase;

class PDFBillingPhone extends PDFSubFieldBase {
    public function GetTestFieldValue()
    {
        return "(555)555-555";
    }

    public function GetWCFieldName()
    {
        return 'billing_phone';
    }
}