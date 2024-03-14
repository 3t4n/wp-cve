<?php
	// Add nonce to the dismissable URL
	$nonce = wp_create_nonce('alp_pro_dismiss_notice');
	$dismissable_url = esc_url(add_query_arg(['alp-pro-settings-ignore-notice' => 'true', 'nonce' => $nonce]));
?>

<div class="admin-message-panel">
	<div class="admin-message-row is-dismissible">
		<h1 class="admin_message_header"><?php esc_html_e('Upgrade to ALP PRO!', 'advanced-local-pickup-for-woocommerce'); ?></h1>
		<p>Upgrade to The Advanced Local Pickup Pro allows you to automate the local pickup workflow, set up multiple pickup locations, pickup appointments, custom emails templates, fulfillment dashboard, and more… </p>
		<p>Get <strong>20% Off</strong> on your first order. Use code <strong>ALPPRO20</strong> to redeem your discount</p>
		<a href="https://www.zorem.com/product/advanced-local-pickup-pro/?utm_source=wp-admin&utm_medium=ALP&utm_campaign=add-ons" class="button-primary btn_pro_notice" target="_blank"><?php esc_html_e('UPGRADE NOW', 'advanced-local-pickup-for-woocommerce'); ?></a>
		<a href="<?php esc_html_e( $dismissable_url ); ?>" class="button-secondary btn_pro_notice"><?php esc_html_e('Dismiss for 30 days', 'advanced-local-pickup-for-woocommerce'); ?></a>
		<a href="<?php esc_html_e( $dismissable_url ); ?>" class="notice-dismiss" style="text-decoration: none;"></a>
	</div>
</div>
