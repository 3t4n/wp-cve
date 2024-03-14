<?php
namespace rnwcinv\htmlgenerator\fields\subfields;
class PDFTaxTotalSubField extends PDFSubFieldBase {
    public function GetTestFieldValue()
    {
        return "45.00";
    }

    public function GetWCFieldName()
    {
        return "";
    }

    public function GetRealFieldValue($format='')
    {
        if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
            $taxText='';
            foreach($this->orderValueRetriever->order->get_tax_totals() as $tax){
                $taxText.='<p style="margin:0;padding:0;"><span style="font-weight: normal;">'.htmlspecialchars_decode($tax->label).'      </span><span>'.$tax->formatted_amount.'</span>';
            }
            return $taxText;
        }else{
            return wc_price($this->orderValueRetriever->order->get_total_tax(),\apply_filters('rnwcinv_format_price', array( 'currency' =>  $this->orderValueRetriever->get('currency') ) ));
        }

    }


}