<?php
class PDFBillingCityConverter extends PDFConverterBase {

    public function GetTestFieldValue()
    {
        return "City";
    }

    public function GetWCFieldName()
    {
        return 'billing_city';
    }
}