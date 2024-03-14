<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	// Add DPD Portugal for WooCommerce nag
	add_action( 'admin_notices', 'webdados_dpd_portugal_pro_nag' );
	function webdados_dpd_portugal_pro_nag() {
		?>
		<script type="text/javascript">
		jQuery(function($) {
			$( document ).on( 'click', '#webdados_dpd_portugal_pro_nag .notice-dismiss', function () {
				//AJAX SET TRANSIENT FOR 90 DAYS
				$.ajax( ajaxurl, {
					type: 'POST',
					data: {
						action: 'dismiss_webdados_dpd_portugal_pro_nag',
					}
				});
			});
		});
		</script>
		<div id="webdados_dpd_portugal_pro_nag" class="notice notice-info is-dismissible">
			<p style="line-height: 1.4em;">
				<img src="<?php echo plugin_dir_url( __FILE__ ) . 'pro.png'; ?>" width="200" height="128" style="float: left; max-width: 100px; height: auto; margin-right: 1em;"/>
				<strong><?php _e( 'Are you still issuing the shipping labels manually on the DPD website?', 'portugal-chronopost-pickup-woocommerce'); ?></strong>
				<span style="font-size: 1.4em;">😱</span>
			</p>
			<p style="line-height: 1.4em;">
				<?php echo sprintf(
					__( 'You should check out our new plugin: %1$sDPD Portugal for WooCommerce%2$s', 'portugal-chronopost-pickup-woocommerce' ),
					'<a href="https://www.webdados.pt/wordpress/plugins/dpd-portugal-para-woocommerce-wordpress/" target="_blank">',
					'</a>'
				); ?>
				<br/>
				<?php echo sprintf(
					__( '%1$sBuy it here%2$s and use the coupon <strong>webdados</strong> for 10%% discount!', 'portugal-chronopost-pickup-woocommerce' ),
					'<a href="https://ptwooplugins.com/product/dpd-portugal-for-woocommerce/" target="_blank">',
					'</a>'
				); ?>
			</p>
		</div>
		<?php
	}
	add_action( 'wp_ajax_dismiss_webdados_dpd_portugal_pro_nag', 'dismiss_webdados_dpd_portugal_pro_nag' );
	function dismiss_webdados_dpd_portugal_pro_nag() {
		$days = 60;
		$expiration = $days * DAY_IN_SECONDS;
		set_transient( 'webdados_dpd_portugal_pro_nag', 1, $expiration );
		wp_die();
	}