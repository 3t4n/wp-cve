<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 9/9/2018
 * Time: 12:48 PM
 */

namespace rnwcinv\compatibility;


use RednaoWooCommercePDFInvoice;

class RemoveGlobalInvoiceNumbers
{
    public function Execute(){
        $currentInvoiceNumber=get_option('rednao_pdf_invoice_number',0);
        global $wpdb;

        $results=$wpdb->get_results('select invoice_id from '.RednaoWooCommercePDFInvoice::$INVOICE_TABLE);

        foreach($results as $result)
        {
            \update_option($result->invoice_id.'_rednao_pdf_invoice_number',apply_filters('wcpdfi_update_latest_invoice_number',$currentInvoiceNumber,$result->invoice_id));
        }
    }
}