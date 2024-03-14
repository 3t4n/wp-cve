<?php

namespace WPDeskFIVendor;

/**
 * Email z fakturą
 */
if (!\defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly
?>

<?php 
\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::woocommerce_email_header_hook($email_heading, $email);
?>

<?php 
if (isset($download_url) && isset($document_name)) {
    \printf(\__('Download Invoice: <a href="%s"><b>%s</b></a>', 'flexible-invoices'), $download_url, $document_name);
    echo '<br/><br/>';
}
?>

<?php 
/**
 * Fires in footer section of email template.
 */
\do_action('woocommerce_email_footer');
