<?php
class PDFGrandTotalConverter extends PDFConverterBase {
    public function GetTestFieldValue()
    {
        return "$495.00";
    }

    public function GetWCFieldName()
    {
        return "formatted_order_total";
    }
}