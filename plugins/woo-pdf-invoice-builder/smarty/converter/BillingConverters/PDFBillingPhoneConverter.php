<?php
class PDFBillingPhoneConverter extends PDFConverterBase {
    public function GetTestFieldValue()
    {
        return "(555)555-555";
    }

    public function GetWCFieldName()
    {
        return 'billing_phone';
    }
}