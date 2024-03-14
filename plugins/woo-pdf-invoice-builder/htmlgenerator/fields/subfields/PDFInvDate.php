<?php

namespace rnwcinv\htmlgenerator\fields\subfields;

class PDFInvDate extends PDFSubFieldBase {
   /* public function __toString()
    {
        $date=null;
        $date=$this->GetFieldValue();
        $format=$this->GetStringValue('format');
        $formattedDate=date($format,$date);
        if(!$formattedDate)
            return 'Invalid Format';
        return $formattedDate;
    }*/
    public function FormatValue($value,$format='')
    {
        $format=$this->GetFieldOptions('format');
        $formattedDate=date_i18n($format,$value);
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

    public function GetRealFieldValue($format='')
    {
        return $this->orderValueRetriever->GetInvoiceDate();
    }



}