<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="notice notice-error wc-szamlazz-notice wc-szamlazz-welcome">
	<div class="wc-szamlazz-welcome-body">
		<button type="button" class="notice-dismiss wc-szamlazz-hide-notice" data-nonce="<?php echo wp_create_nonce( 'wc-szamlazz-hide-notice' )?>" data-notice="pro_expiration"><span class="screen-reader-text"><?php esc_html_e( 'Dismiss', 'woocommerce' ); ?></span></button>
		<h2><?php _e( 'Számlázz.hu', 'wc-szamlazz' ); ?> - <?php _e('The PRO version is expired', 'wc-szamlazz'); ?></h2>
		<p>
			<?php echo __('The license key for the PRO version is expired.', 'wc-szamlazz'); ?>
		</p>
		<p>
			<a class="button-secondary" href="<?php echo esc_url(admin_url( 'admin.php?page=wc-settings&tab=integration&section=wc_szamlazz')); ?>"><?php esc_html_e( 'Go to Settings', 'wc-szamlazz' ); ?></a>
		</p>
	</div>
</div>
