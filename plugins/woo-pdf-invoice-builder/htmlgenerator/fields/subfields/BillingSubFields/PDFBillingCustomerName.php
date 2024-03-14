<?php
namespace rnwcinv\htmlgenerator\fields\subfields\BillingSubFields;
use rnwcinv\htmlgenerator\fields\subfields\PDFSubFieldBase;

class PDFBillingCustomerName extends PDFSubFieldBase {
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
        return $this->orderValueRetriever->get('billing_first_name').' '. $this->orderValueRetriever->get('billing_last_name');
    }


}