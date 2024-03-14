<?php
namespace rnwcinv\htmlgenerator\fields\subfields\ShippingSubFields;
use rnwcinv\htmlgenerator\fields\subfields\PDFSubFieldBase;

class PDFShippingAddress extends PDFSubFieldBase {
    public function GetTestFieldValue()
    {
        return "Address goes here #323";
    }

    public function GetRealFieldValue($format='')
    {
        $address=$this->orderValueRetriever->get('shipping_address_1');
        $address2=$this->orderValueRetriever->get('shipping_address_2');

        if(strlen(trim($address2))>0)
        {
            if($format=='plain')
                $address.=" ".$address2;
            else
                $address.="<br/>".$address2;
        }
        return $address;
    }


    public function GetWCFieldName()
    {
        return '';
    }
}