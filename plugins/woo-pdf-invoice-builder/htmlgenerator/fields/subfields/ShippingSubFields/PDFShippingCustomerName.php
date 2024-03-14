<?php
namespace rnwcinv\htmlgenerator\fields\subfields\ShippingSubFields;
use rnwcinv\htmlgenerator\fields\subfields\PDFSubFieldBase;

class PDFShippingCustomerName extends PDFSubFieldBase {
    public function GetTestFieldValue()
    {
        return "Customer Name";
    }

    public function GetWCFieldName()
    {
        return '';
    }

    public function GetRealFieldValue($format='')
    {
        return $this->orderValueRetriever->get('shipping_first_name').' '. $this->orderValueRetriever->get('shipping_last_name');
    }


}