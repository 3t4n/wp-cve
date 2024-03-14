<?php
class PDFCustomerNotesConverter extends PDFConverterBase {
    public function GetTestFieldValue()
    {
        return "Please make sure to deliver the items before sunday.";
    }

    public function GetWCFieldName()
    {
        return "customer_note";
    }
}