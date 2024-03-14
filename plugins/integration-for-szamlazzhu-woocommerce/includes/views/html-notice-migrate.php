<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="notice notice-info wc-szamlazz-notice wc-szamlazz-welcome">
	<p><strong><?php esc_html_e('Számlázz.hu database update required', 'wc-szamlazz'); ?></strong></p>
	<p><?php esc_html_e('WooCommerce Számlázz.hu updated! For everything to continue to work, the database must also be updated to the latest version.', 'wc-szamlazz'); ?></p>
	<p><?php esc_html_e('The database update runs in the background and may take some time (especially if you have lots of orders), please be patient.', 'wc-szamlazz'); ?></p>
	<p>
		<a class="button-primary wc-szamlazz-migrate-button" data-nonce="<?php echo wp_create_nonce( 'wc-szamlazz-migrate' )?>" href="<?php echo esc_url(admin_url( 'admin.php?page=wc-settings&tab=integration&section=wc_szamlazz' )); ?>"><?php esc_html_e( 'Update database', 'wc-szamlazz' ); ?></a>
	</p>
</div>
