<?php
class PDFShippingTotalConverter extends PDFConverterBase {
    public function GetTestFieldValue()
    {
        return "$15.00";
    }

    public function GetWCFieldName()
    {
        return "";
    }

    public function GetRealFieldValue()
    {
        return $this->order->GetTotal('shipping');
    }
}