<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$placeholders = array( '{shop_name}', '{order_id}', '{order_count}', '{order_amount}', '{billing_name}', '{billing_first}', '{billing_last}', '{shipping_name}', '{shipping_first}', '{shipping_last}' );

if ( trackship_for_woocommerce()->is_ast_active() ) {
	$placeholders[] = '{tracking_number}';
	$placeholders[] = '{tracking_provider}';
	$placeholders[] = '{tracking_link}';
}

if ( is_trackship_connected() ) {
	$placeholders[] = '{shipment_status}';
	$placeholders[] = '{est_delivery_date}';
}

?>
<div class="zorem_plugin_sidebar_section">
	<div class="zorem_plugin_sidebar_section_header">
		<h3><?php esc_html_e('Available placeholders', 'trackship-for-woocommerce' ); ?></h3>
	</div>
	<div class="zorem_plugin_sidebar_section_content">
		<p>
			<?php foreach ( $placeholders as $placeholder ) { ?>
				<code class="btn clipboard placeholder" title="copied!" data-clipboard-text="<?php echo esc_html($placeholder); ?>" >
					<?php echo esc_html($placeholder); ?>
				</code>
			<?php } ?>
			<br>
			<a href="https://www.zorem.com/docs/sms-for-woocommerce/sms-notifications/#available-placeholders-for-orders-sms" target="_blank"><?php esc_html_e('More info', 'trackship-for-woocommerce' ); ?></a>
		</p>
	</div>
</div>
