<?php
class PDFShippingCityConverter extends PDFConverterBase {

    public function GetTestFieldValue()
    {
        return "City";
    }

    public function GetWCFieldName()
    {
        return 'shipping_city';
    }
}