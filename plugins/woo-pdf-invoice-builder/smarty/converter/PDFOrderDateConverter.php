<?php
class PDFOrderDateConverter extends PDFConverterBase {
    public function __toString()
    {
        $date=null;
        $date=$this->GetFieldValue();
        $format=$this->GetStringValue('format');
        $formattedDate=date($format,$date);
        if(!$formattedDate)
            return 'Invalid Format';
        return $formattedDate;
    }


    public function GetTestFieldValue()
    {
        return strtotime('today');
    }

    public function GetWCFieldName()
    {
        return "order_date";
    }


}