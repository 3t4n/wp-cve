<?php

namespace WPDeskFIVendor;

/**
 * Email z fakturą (plain text)
 */
/**
 * @var $order WC_Order
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks;
if (!\defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly
echo $email_heading . "\n\n";
echo "****************************************************\n\n";
if (isset($download_url)) {
    \printf(\__('Download Invoice: %s', 'flexible-invoices'), $download_url) . "\n\n";
}
echo \sprintf(\esc_html__('Order number: %s', 'woocommerce'), $order->get_order_number()) . "\n";
echo \sprintf(\esc_html__('Order date: %s', 'woocommerce'), \date_i18n(\wc_date_format(), \strtotime($order->get_date_created()))) . "\n";
\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::woocommerce_email_order_meta_hook($order, $sent_to_admin, $plain_text, $email);
echo "\n";
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Email\BaseEmail::get_email_order_items($order, \true);
echo "----------\n\n";
if ($totals = $order->get_order_item_totals()) {
    foreach ($totals as $total) {
        echo $total['label'] . "\t " . $total['value'] . "\n";
        // do not escape HTML!
    }
}
echo "\n****************************************************\n\n";
\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::woocommerce_email_after_order_table_hook($order, $sent_to_admin, $plain_text, $email);
\esc_html_e('Your details', 'woocommerce') . "\n\n";
if ($order->get_billing_email()) {
    \esc_html_e('Email', 'woocommerce');
}
?>: <?php 
echo $order->get_billing_email() . "\n";
if ($order->get_billing_phone()) {
    \esc_html_e('Phone', 'woocommerce');
}
?>: <?php 
echo $order->get_billing_phone() . "\n";
\wc_get_template('emails/plain/email-addresses.php', ['order' => $order]);
echo "\n****************************************************\n\n";
echo \apply_filters('woocommerce_email_footer_text', \get_option('woocommerce_email_footer_text'));
