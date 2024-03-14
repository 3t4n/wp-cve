<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="notice notice-info wc-szamlazz-notice wc-szamlazz-welcome">
	<button type="button" class="notice-dismiss wc-szamlazz-hide-notice" data-nonce="<?php echo wp_create_nonce( 'wc-szamlazz-hide-notice' )?>" data-notice="migrated"><span class="screen-reader-text"><?php esc_html_e( 'Dismiss', 'woocommerce' ); ?></span></button>
	<p><?php esc_html_e('WooCommerce Számlázz.hu has finished updating its database. Thank you for using the latest version!', 'wc-szamlazz'); ?></p>
	<p><?php esc_html_e('Since a lot has changed in this version, please review the settings and the newly generated invoices to make sure everything works well for!', 'wc-szamlazz'); ?></p>
</div>
