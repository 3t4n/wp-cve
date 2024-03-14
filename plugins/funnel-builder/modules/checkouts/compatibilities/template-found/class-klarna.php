<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Official Klarna Payments for WooCommerce (https://wordpress.org/plugins/klarna-payments-for-woocommerce/)
 * Payment Plugins for Stripe WooCommerce (Klarna) (https://wordpress.org/plugins/woo-stripe-payment/)
 */
#[AllowDynamicProperties] 

  class WFACP_Klarna {
	public function __construct() {
		add_action( 'wfacp_internal_css', [ $this, 'disable_pop_state' ] );
		add_action( 'woocommerce_checkout_order_processed', [ $this, 'attach_hooks' ] );

	}


	public function disable_pop_state() {
		?>
        <script>
            window.addEventListener('bwf_checkout_load', function () {
                wfacp_frontend.hooks.addFilter('disable_pop_state', function (status, newHash) {
                    if ('' !== newHash && newHash.indexOf('#kp') > -1) {
                        status = true;
                    }
                    return status;
                });
            })
        </script>
		<?php
	}

	public function attach_hooks() {
		add_filter( 'woocommerce_get_checkout_url', [ $this, 'change_checkout_url' ] );
	}

	public function change_checkout_url( $url ) {
		if ( ! isset( $_REQUEST['payment_method'] ) ) {
			return $url;
		}
		$payment_method = $_REQUEST['payment_method'];
		if ( ! in_array( $payment_method, [ 'klarna_payments', 'klarna_payments_pay_later', 'klarna_payments_pay_over_time', 'stripe_klarna' ] ) ) {
			return $url;
		}
		if ( ! isset( $_REQUEST['_wfacp_post_id'] ) || empty( $_REQUEST['_wfacp_post_id'] ) ) {
			return $url;
		}

		$aero_id   = $_REQUEST['_wfacp_post_id'];
		$is_global = $_REQUEST['wfacp_is_checkout_override'];
		$global_id = WFACP_Common::get_checkout_page_id();
		if ( 'yes' == $is_global ) {
			$global_post = get_post( $global_id );
			if ( ! is_null( $global_post ) && $global_post->post_type !== WFACP_Common::get_post_type_slug() ) {
				$url = get_the_permalink( $global_id );
			}
		} else {
			if ( isset( $_REQUEST['wfacp_embed_form_page_id'] ) ) {
				$url = get_the_permalink( $_REQUEST['wfacp_embed_form_page_id'] );
			} else {
				$url = get_the_permalink( $aero_id );
			}
		}

		return $url;
	}


}


new WFACP_Klarna();




