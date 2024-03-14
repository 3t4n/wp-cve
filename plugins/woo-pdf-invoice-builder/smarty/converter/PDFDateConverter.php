<?php
class PDFDateConverter extends PDFConverterBase {
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
        return "date_completed";
    }

    public function GetRealFieldValue()
    {
        return get_post_meta( $this->order->GetId(),'REDNAO_WCPDFI_INVOICE_DATE',true);
    }


}