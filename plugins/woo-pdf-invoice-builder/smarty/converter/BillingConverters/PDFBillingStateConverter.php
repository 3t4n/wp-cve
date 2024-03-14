<?php
class PDFBillingStateConverter extends PDFConverterBase {
    public function GetTestFieldValue()
    {
        return "State";
    }

    public function GetWCFieldName()
    {
        return 'billing_state';
    }
}