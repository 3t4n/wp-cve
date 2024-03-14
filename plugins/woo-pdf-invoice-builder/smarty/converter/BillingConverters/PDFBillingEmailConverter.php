<?php
class PDFBillingEmailConverter extends PDFConverterBase {
    public function GetTestFieldValue()
    {
        return "Customer@email.com";
    }

    public function GetWCFieldName()
    {
        return "billing_email";
    }
}