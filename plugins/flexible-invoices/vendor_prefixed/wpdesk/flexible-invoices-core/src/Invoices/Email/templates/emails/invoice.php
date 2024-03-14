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
<h2><?php 
\esc_html_e('Order', 'woocommerce') . ': ' . $order->get_order_number();
?> (<?php 
\printf('<time datetime="%s">%s</time>', \date_i18n('c', \strtotime($order->get_date_created())), \date_i18n(\wc_date_format(), \strtotime($order->get_date_created())));
?>)</h2>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php 
\esc_html_e('Product', 'woocommerce');
?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php 
\esc_html_e('Quantity', 'woocommerce');
?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php 
\esc_html_e('Price', 'woocommerce');
?></th>
		</tr>
	</thead>
	<tbody>
	<?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Email\BaseEmail::get_email_order_items($order);
?>
	</tbody>
	<tfoot>
		<?php 
if ($totals = $order->get_order_item_totals()) {
    $i = 0;
    foreach ($totals as $total) {
        $i++;
        // do not escape HTML for total items!
        ?><tr>
						<th scope="row" colspan="2" style="text-align:left; border: 1px solid #eee; <?php 
        if ($i === 1) {
            echo 'border-top-width: 4px;';
        }
        ?>"><?php 
        echo $total['label'];
        ?></th>
						<td style="text-align:left; border: 1px solid #eee; <?php 
        if ($i === 1) {
            echo 'border-top-width: 4px;';
        }
        ?>"><?php 
        echo $total['value'];
        ?></td>
					</tr><?php 
    }
}
?>
	</tfoot>
</table>

<?php 
\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::woocommerce_email_after_order_table_hook($order, $sent_to_admin, $plain_text, $email);
\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::woocommerce_email_order_meta_hook($order, $sent_to_admin, $plain_text, $email);
?>
<br/><br/>
<h2><?php 
\esc_html_e('Customer details', 'woocommerce');
?></h2>

<?php 
if ($order->get_billing_email()) {
    ?>
	<p><strong><?php 
    \esc_html_e('Email', 'woocommerce');
    ?>: </strong> <?php 
    echo \esc_html($order->get_billing_email());
    ?></p>
<?php 
}
if ($order->get_billing_phone()) {
    ?>
	<p><strong><?php 
    \esc_html_e('Phone', 'woocommerce');
    ?>: </strong> <?php 
    echo \esc_html($order->get_billing_phone());
    ?></p>
<?php 
}
?>

<?php 
\wc_get_template('emails/email-addresses.php', ['order' => $order]);
?>

<?php 
/**
 * Fires in footer section of email template.
 */
\do_action('woocommerce_email_footer');
