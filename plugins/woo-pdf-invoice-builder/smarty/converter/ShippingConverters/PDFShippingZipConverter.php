<?php
class PDFShippingZipConverter extends PDFConverterBase {
    public function GetTestFieldValue()
    {
        return "Zip";
    }

    public function GetWCFieldName()
    {
        return "shipping_postcode";
    }
}