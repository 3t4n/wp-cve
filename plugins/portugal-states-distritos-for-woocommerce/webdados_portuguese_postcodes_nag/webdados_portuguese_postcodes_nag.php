<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	// Add Portuguese Postcodes for WooCommerce nag
	add_action( 'admin_notices', 'webdados_portuguese_postcodes_nag' );
	function webdados_portuguese_postcodes_nag() {
		?>
		<script type="text/javascript">
		jQuery(function($) {
			$( document ).on( 'click', '#webdados_portuguese_postcodes_nag .notice-dismiss', function () {
				//AJAX SET TRANSIENT FOR 90 DAYS
				$.ajax( ajaxurl, {
					type: 'POST',
					data: {
						action: 'dismiss_webdados_portuguese_postcodes_nag',
					}
				});
			});
		});
		</script>
		<div id="webdados_portuguese_postcodes_nag" class="notice notice-info is-dismissible">
			<p style="line-height: 1.4em;">
				<img src="<?php echo plugin_dir_url( __FILE__ ) . 'icon-portuguese-postcodes.svg'; ?>" width="70" height="70" style="float: left; max-width: 70px; height: auto; margin-right: 1em;"/>
				<strong><?php _e( 'Do your customers still write the full address manually on the checkout?', 'portugal-states-distritos-for-woocommerce'); ?></strong>
				<br/>
				<?php echo sprintf(
					__( 'Activate the automatic filling of the address details at the checkout, including street name and neighbourhood, based on the postal, avoiding incorrect data at the time of shipping, with our plugin %1$sPortuguese Postcodes for WooCommerce%2$s', 'portugal-states-distritos-for-woocommerce' ),
					'<a href="https://ptwooplugins.com/product/portuguese-postcodes-for-woocommerce-technical-support/" target="_blank">',
					'</a>'
				); ?>
				<br/>
				<?php _e( 'Use the coupon <strong>webdados</strong> for 10% discount!', 'portugal-states-distritos-for-woocommerce' ); ?>
			</p>
		</div>
		<?php
	}
	add_action( 'wp_ajax_dismiss_webdados_portuguese_postcodes_nag', 'dismiss_webdados_portuguese_postcodes_nag' );
	function dismiss_webdados_portuguese_postcodes_nag() {
		$days = 90;
		$expiration = $days * DAY_IN_SECONDS;
		set_transient( 'webdados_portuguese_postcodes_nag', 1, $expiration );
		wp_die();
	}