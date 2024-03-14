<?php
class PDFShippingPhoneConverter extends PDFConverterBase {
    public function GetTestFieldValue()
    {
        return "(555)555-555";
    }

    public function GetWCFieldName()
    {
        return 'shipping_phone';
    }
}