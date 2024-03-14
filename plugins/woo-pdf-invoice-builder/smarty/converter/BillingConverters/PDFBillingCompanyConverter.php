<?php
class PDFBillingCompanyConverter extends PDFConverterBase {
    public function GetTestFieldValue()
    {
        return "Awesome Company";
    }

    public function GetWCFieldName()
    {
        return 'billing_company';
    }
}