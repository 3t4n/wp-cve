<?php
class PDFSubTotalConverter extends PDFConverterBase {
    public function GetTestFieldValue()
    {
        return "$435.00";
    }

    public function GetWCFieldName()
    {
        return "";
    }

    public function GetRealFieldValue()
    {
        return $this->order->GetTotal('cart_subtotal');
    }


}