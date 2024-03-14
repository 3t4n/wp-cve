<?php
namespace rnwcinv\htmlgenerator\fields\subfields;
class PDFCustomerNotesSubField extends PDFSubFieldBase {
    public function GetTestFieldValue()
    {
        return "Please make sure to deliver the items before sunday.";
    }

    public function GetWCFieldName()
    {
        return "customer_note";
    }

    public function GetRealFieldValue($format=''){
        return nl2br($this->orderValueRetriever->get($this->GetWCFieldName()));
    }
}