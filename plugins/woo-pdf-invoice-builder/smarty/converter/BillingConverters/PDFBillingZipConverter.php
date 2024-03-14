<?php
class PDFBillingZipConverter extends PDFConverterBase {
    public function GetTestFieldValue()
    {
        return "Zip";
    }

    public function GetWCFieldName()
    {
        return "billing_postcode";
    }
}