<?php
namespace rnwcinv\htmlgenerator\fields\subfields\ShippingSubFields;
use rnwcinv\htmlgenerator\fields\subfields\PDFSubFieldBase;

class PDFShippingState extends PDFSubFieldBase {
    public function GetTestFieldValue()
    {
        return "State";
    }

    public function GetWCFieldName()
    {
        return 'shipping_state';
    }

    public function FormatValue($value, $format = '')
    {
        $country= $this->orderValueRetriever->get('shipping_country');
        $state= $value;
        if($this->GetBooleanValue('showStateFullName',false)&&isset(WC()->countries->get_states($country)[$state]))
            $state=WC()->countries->get_states($country)[$state];

        return \html_entity_decode($state);
    }
}