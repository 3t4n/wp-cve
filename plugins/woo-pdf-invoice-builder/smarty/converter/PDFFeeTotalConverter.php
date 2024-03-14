<?php
class PDFFeeTotalConverter extends PDFConverterBase {
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
        $fees='';
        foreach($this->order->primaryOrder->get_fees() as $fee){
            $fees.='<p style="margin:0;padding:0;"><span style="font-weight: normal;">'.htmlspecialchars_decode($fee->get_name()).
                '      </span><span>'.
                            wc_price($fee->get_total(),\apply_filters('rnwcinv_format_price', array( 'currency' =>  $this->order->get('currency') )) )
                        .'</span></p>';
        }
        return $fees;
    }


}