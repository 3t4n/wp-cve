<?php
class PDFShippingStateConverter extends PDFConverterBase {
    public function GetTestFieldValue()
    {
        return "State";
    }

    public function GetWCFieldName()
    {
        return 'shipping_state';
    }
}