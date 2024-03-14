<?php
class PDFInvoiceNumberConverter extends PDFConverterBase {
    public function __toString()
    {
        $prefix=$this->GetStringValue('prefix');
        $sufix=$this->GetStringValue('sufix');
        $digits=$this->GetNumericValue('digits');

        $number=$this->GetFieldValue();

        if(is_numeric($number)&&$digits>0)
            $number=str_pad(intval($number),$digits,'0',STR_PAD_LEFT);

        return $prefix.$number.$sufix;


    }

    public function GetRealFieldValue()
    {
        $seqType=$this->GetStringValue('seq_type');
        if($seqType=='seq')
        {
            $number=get_post_meta( $this->order->GetId(),'REDNAO_WCPDFI_INVOICE_ID',true);
            if(is_numeric($number))
                $number=intval($number);
            else
                return 'N/A';
        }else{
            $number=$this->GetFieldValueFromOrder();
        }
        return $number;
    }


    public function GetTestFieldValue()
    {
        return "1";
    }

    public function GetWCFieldName()
    {
        return "order_number";
    }
}