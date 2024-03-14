<?php
namespace rnwcinv\htmlgenerator\fields\subfields;
class PDFInvoiceNumberSubField extends PDFSubFieldBase {


    public function GetRealFieldValue($format='')
    {
        return $this->orderValueRetriever->GetFormattedInvoiceNumber();
    }

    public function FormatValue($value,$format='')
    {
        return $value;
    }


    public function GetTestFieldValue()
    {
        return $this->orderValueRetriever->GetFormattedInvoiceNumber();
    }

    public function GetWCFieldName()
    {
        return "order_number";
    }
}