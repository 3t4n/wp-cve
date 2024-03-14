<?php
namespace rnwcinv\htmlgenerator\fields\subfields;
class PDFStatusSubField extends PDFSubFieldBase {
    public function GetTestFieldValue()
    {
        return "Completed";
    }

    public function GetWCFieldName()
    {
        return "customer_note";
    }

    public function GetRealFieldValue($format=''){
        return wc_get_order_status_name($this->orderValueRetriever->order->get_status());
    }
}