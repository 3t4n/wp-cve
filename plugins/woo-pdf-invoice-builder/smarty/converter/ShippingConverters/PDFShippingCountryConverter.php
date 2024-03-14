<?php
class PDFShippingCountryConverter extends PDFConverterBase {

    public function GetTestFieldValue()
    {
        return "Country";
    }

    public function GetWCFieldName()
    {
        return "shipping_country";
    }
}