<?php
class PDFShippingEmailConverter extends PDFConverterBase {
    public function GetTestFieldValue()
    {
        return "Customer@email.com";
    }

    public function GetWCFieldName()
    {
        return "shipping_email";
    }
}