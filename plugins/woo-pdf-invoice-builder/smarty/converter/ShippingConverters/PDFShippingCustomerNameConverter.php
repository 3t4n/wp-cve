<?php
class PDFShippingCustomerNameConverter extends PDFConverterBase {
    public function GetTestFieldValue()
    {
        return "Customer Name";
    }

    public function GetWCFieldName()
    {
        return '';
    }

    public function GetRealFieldValue()
    {
        return $this->order->get('shipping_first_name').' '. $this->order->get('shipping_last_name');
    }


}