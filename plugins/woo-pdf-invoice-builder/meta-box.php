<?php
class RedNaoWooCommerceMetaBox{
    /**
     * @var WP_Post
     */
    public $post;
    public function __construct($post)
    {
        global $typenow;


        $this->post=$post;
        $this->StartTable();
/*
        $invoiceId=get_post_meta($this->post->ID,'REDNAO_WCPDFI_INVOICE_ID',true);
        $invoiceDate=get_post_meta($this->post->ID,'REDNAO_WCPDFI_INVOICE_DATE',true);
        $buttonText='';
        if($invoiceId=='')
        {
            $this->CreateTextRow('The invoice has not been created yet');
            $buttonText='Create Invoice';
        }else
        {

            $this->CreateRow('Invoice Number', $invoiceId);
            $this->CreateRow('Date', date('F j, Y', $invoiceDate));
            $buttonText='View Invoice';
        }
        $this->CreateViewButton($buttonText,$this->post->ID);*/
        $this->EndTable();


    }

    private function StartTable()
    {

        echo '<div id="rniotb-invoice-meta" class="rednao rednao-wcpdfi-metabox" style="width: 100%"><div style="text-align: center"><img src="'.get_admin_url(null,'images/spinner-2x.gif').'\')."/></div>';
    }

    private function EndTable()
    {
        echo '</div>';
    }

    private function CreateRow($label, $value)
    {
        echo '<tr>';
        echo '<td>'.esc_html($label).'</td>';
        echo '<td style="font-weight:bold;text-align: right">'.esc_html($value).'</td>';
        echo '</tr>';
    }

    private function CreateViewButton($label,$orderId)
    {
        global $wpdb;
        $results=$wpdb->get_results('select invoice_id,name from '.RednaoWooCommercePDFInvoice::$INVOICE_TABLE,'ARRAY_A');
        echo '<tr><td colspan="2">';
        $url=wp_nonce_url( admin_url( "admin-ajax.php?action=rednao_wcpdfinv_generate_pdf&orderid=" . $orderId ), 'rednao_wcpdfinv_generate_pdf_'.$orderId );
        printf( '<a  class="button woo-pdf-invoice-view" href="#" >%1$s</a>', $label);
        echo '<input  class="woo-pdf-invoice-nounce"  type="hidden" value="'.$url.'"/>';
        $disabled='';
        if(count($results)<=1)
        {
            $disabled='display:none;';
        }
        echo '<select class="woo-pdf-invoice-list" style="float: right;'.$disabled.'">';
        foreach($results as $result)
        {
            $selected='';
            if($result==$results[0])
            {
                $selected = 'selected="selected"';
            }
            echo '<option '.$selected.' value="'.$result['invoice_id'].'">'.esc_html($result['name']).'</option>';
        }

        echo '</select>';
        echo '</td></tr>';
    }

    private function CreateTextRow($text)
    {
        echo '<tr>';
        echo '<td colspan="2">'.esc_html($text).'</td>';
        echo '</tr>';
    }

}