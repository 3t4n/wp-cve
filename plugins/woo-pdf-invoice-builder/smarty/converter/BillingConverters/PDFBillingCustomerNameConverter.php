<?php
class PDFBillingCustomerNameConverter extends PDFConverterBase {
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
        return $this->order->get('billing_first_name').' '. $this->order->get('billing_last_name');
    }


}