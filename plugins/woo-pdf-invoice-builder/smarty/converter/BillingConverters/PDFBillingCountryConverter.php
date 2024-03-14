<?php
class PDFBillingCountryConverter extends PDFConverterBase {

    public function GetTestFieldValue()
    {
        return "Country";
    }

    public function GetWCFieldName()
    {
        return "billing_country";
    }
}