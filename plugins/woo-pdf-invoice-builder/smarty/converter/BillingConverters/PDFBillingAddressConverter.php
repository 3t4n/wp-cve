<?php
class PDFBillingAddressConverter extends PDFConverterBase {
    public function GetTestFieldValue()
    {
        return "Address goes here #323";
    }

    public function GetRealFieldValue()
    {
        $address=$this->order->get('billing_address_1');
        $address2=$this->order->get('billing_address_2');

        if(strlen(trim($address2))>0)
            $address.="<br/>".$address2;
        return $address;
    }


    public function GetWCFieldName()
    {
        return '';
    }
}