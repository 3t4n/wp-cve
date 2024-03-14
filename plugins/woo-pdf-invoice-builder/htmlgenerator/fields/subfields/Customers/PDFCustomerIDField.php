<?php


namespace rnwcinv\htmlgenerator\fields\subfields\Customers;


use rnwcinv\htmlgenerator\fields\subfields\PDFSubFieldBase;

class PDFCustomerIDField extends PDFSubFieldBase {
    public function GetTestFieldValue()
    {
        return "10";
    }

    public function GetWCFieldName()
    {
        return "";
    }

    public function GetRealFieldValue($format='')
    {
        $customerId= $this->orderValueRetriever->order->get_customer_id();
        $user=\get_userdata($customerId);
        if($user==false)
            return '';
        return $customerId;
    }


}