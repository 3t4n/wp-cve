<?php
class PDFPaymentMethodConverter extends PDFConverterBase {
    public function GetTestFieldValue()
    {
        return "Credit";
    }

    public function GetWCFieldName()
    {
        return "payment_method_title";
    }
}