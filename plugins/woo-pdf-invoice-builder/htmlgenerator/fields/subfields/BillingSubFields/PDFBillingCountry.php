<?php
namespace rnwcinv\htmlgenerator\fields\subfields\BillingSubFields;
use rnwcinv\htmlgenerator\fields\subfields\PDFSubFieldBase;

class PDFBillingCountry extends PDFSubFieldBase {

    public function GetTestFieldValue()
    {
        return "Country";
    }

    public function GetWCFieldName()
    {
        return "billing_country";
    }

    public function FormatValue($value, $format = '')
    {
        $country= $this->orderValueRetriever->get('billing_country');
        $state= $value;
        if($this->GetBooleanValue('showFullName',false)&&isset(WC()->countries->get_states($country)[$state]))
            $state=WC()->countries->get_states($country)[$state];

        return \html_entity_decode($state);
    }

}