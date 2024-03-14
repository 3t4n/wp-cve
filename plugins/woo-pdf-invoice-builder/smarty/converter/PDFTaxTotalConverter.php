<?php
class PDFTaxTotalConverter extends PDFConverterBase {
    public function GetTestFieldValue()
    {
        return "45.00";
    }

    public function GetWCFieldName()
    {
        return "";
    }

    public function GetRealFieldValue()
    {
        if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
            $taxText='';
            foreach($this->order->primaryOrder->get_tax_totals() as $tax){
                $taxText.='<p style="margin:0;padding:0;"><span style="font-weight: normal;">'.htmlspecialchars_decode($tax->label).'      </span><span>'.$tax->formatted_amount.'</span>';
            }
            return $taxText;
        }else{
            return wc_price($this->order->primaryOrder->get_total_tax(), \apply_filters('rnwcinv_format_price',array( 'currency' =>  $this->order->get('currency') )) );
        }

    }


}