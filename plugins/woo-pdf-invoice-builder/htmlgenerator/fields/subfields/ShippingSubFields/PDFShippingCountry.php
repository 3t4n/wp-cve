<?php
namespace rnwcinv\htmlgenerator\fields\subfields\ShippingSubFields;
use rnwcinv\htmlgenerator\fields\subfields\PDFSubFieldBase;

class PDFShippingCountry extends PDFSubFieldBase {

    public function GetTestFieldValue()
    {
        return "Country";
    }

    public function GetWCFieldName()
    {
        return "shipping_country";
    }

    public function FormatValue($value, $format = '')
    {

        if($this->GetBooleanValue('showFullName',false)&&isset(WC()->countries->countries[$value]))
            $value=WC()->countries->countries[$value];

        return $value;
    }
}