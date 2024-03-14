<?php
class PDFShippingCompanyConverter extends PDFConverterBase {
    public function GetTestFieldValue()
    {
        return "Awesome Company";
    }

    public function GetWCFieldName()
    {
        return 'shipping_company';
    }
}