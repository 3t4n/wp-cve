<?php
class PDFDiscountTotalConverter extends PDFConverterBase {
    public function GetTestFieldValue()
    {
        return "0.00";
    }

    public function GetWCFieldName()
    {
        return "";
    }

    public function GetRealFieldValue()
    {
        return $this->order->GetTotal('discount');

    }


}