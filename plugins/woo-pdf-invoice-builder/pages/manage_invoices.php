
<div id="AppRot"></div>

<style>
    body{
        background-color: white;
    }

    .MuiTablePagination-spacer-047{
        display: none;
    }
</style>

<?php

wp_enqueue_script('wcrbc-pdfbuilder-manage-invoices',RednaoWooCommercePDFInvoice::$URL.'js/dist/manageInvoices_bundle.js','jquery');

global $wpdb;

$DefaultPrinterId='';
$DefaultPrinterLabel='';

if(RednaoWooCommercePDFInvoice::IsPR())
{
    $DefaultPrinterLabel=\rnwcinv\pr\utilities\Printer\Printer::GetDefaultPrinterLabel();
    $DefaultPrinterId=\rnwcinv\pr\utilities\Printer\Printer::GetDefaultPrinter();
}
$results=$wpdb->get_results('select name Name,invoice_id InvoiceId from '.RednaoWooCommercePDFInvoice::$INVOICE_TABLE);
wp_localize_script('wcrbc-pdfbuilder-manage-invoices','rednaoparamsManageInvoice',array(
        'Invoices'=>$results,
        'Nonce'=>wp_create_nonce('pdfi_manage_nonce'),
        'DefaultPrinterId'=>$DefaultPrinterId,
        'DefaultPrinterLabel'=>$DefaultPrinterLabel,
        'ViewOrderURL'=>get_admin_url( null, 'post.php?action=edit&post='),
        'IsPr'=>RednaoWooCommercePDFInvoice::IsPR(),
));
?>

