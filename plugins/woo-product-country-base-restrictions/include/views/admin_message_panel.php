<?php
// Add nonce to the dismissable URL
$nonce = wp_create_nonce('cbr_dismiss_notice');
$dismissable_url = esc_url(add_query_arg(['cbr-pro-plugin-ignore-notice' => 'true', 'nonce' => $nonce]));
?>
<div class="admin-message-panel">
	<div class="admin-message-row is-dismissible">
		<h1 class="admin_message_header"><?php esc_html_e('Upgrade to CBR PRO!', 'woo-product-country-base-restrictions'); ?></h1>
		<p>Upgrade to Country Based Restrictions Pro and save time by applying bulk country restrictions by the product categories, tags, attributes and shipping classes. Enable and disable payment gateways by country and more...</p>
		<p>Get <strong>20% Off</strong> on your first order. Use code <strong>CBRPRO20</strong> to redeem your discount</p>
		<a href="https://www.zorem.com/product/country-based-restriction-pro/?utm_source=wp-admin&utm_medium=CBR&utm_campaign=add-ons" class="button-primary btn_pro_notice" target="_blank"><?php esc_html_e('UPGRADE NOW', 'woo-product-country-base-restrictions'); ?></a>
		<a href="<?php esc_html_e( $dismissable_url ); ?>" class="button-secondary btn_pro_notice"><?php esc_html_e('Dismiss for 30 days', 'woo-product-country-base-restrictions'); ?></a>
		<a href="<?php esc_html_e( $dismissable_url ); ?>" class="notice-dismiss" style="text-decoration: none;"></a>
	</div>
</div>
