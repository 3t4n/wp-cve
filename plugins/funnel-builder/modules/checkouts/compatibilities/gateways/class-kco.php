<?php

/**
 * Klarna Checkout BY Klarna Krokedril
 *
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_Klarna_checkout
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_Klarna_checkout {
	public function __construct() {
		add_filter( 'wfacp_skip_checkout_page_detection', [ $this, 'disable_checkout_page_if_klarna_checkout_set' ], 100 );
		add_action( 'woocommerce_checkout_update_order_review', [ $this, 'remove_kco_event' ], 100 );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_klarna_checkout_redirection' ] );
		add_action( 'in_admin_header', array( $this, 'klarna_checkout_installed' ), 15 );
	}

	public function disable_checkout_page_if_klarna_checkout_set( $status ) {
		if ( is_null( WC()->cart ) || is_null( WC()->session ) || WC()->cart->is_empty() || false === WFACP_Core()->public->is_checkout_override() ) {
			return $status;
		}

		$current_gateway = WC()->session->get( 'chosen_payment_method', '' );

		if ( 'kco' !== $current_gateway ) {
			return $status;
		}

		return true;
	}

	public function remove_kco_event() {
		if ( isset( $_REQUEST['wfacp_id'] ) && $_REQUEST['wfacp_id'] > 0 ) {
			WFACP_Common::remove_actions( 'woocommerce_checkout_cart_item_quantity', 'KCO', 'add_quantity_field' );
			WFACP_Common::remove_actions( 'woocommerce_checkout_cart_item_quantity', 'CSI_Frontend', 'checkout_cart_item_name' );
		}
	}

	public function remove_klarna_checkout_redirection() {
		if ( ! class_exists( 'KCO_Templates' ) || ! method_exists( 'KCO_Templates', 'get_instance' ) ) {
			return;
		}

		$Klarna_Checkout_For_WooCommerce_Templates = \KCO_Templates::get_instance();
		remove_action( 'wp_footer', array( $Klarna_Checkout_For_WooCommerce_Templates, 'check_that_kco_template_has_loaded' ) );
	}

	public function klarna_checkout_installed() {
		?>
        <div class="error" style="margin-top:74px">
            <p>
				<?php
				_e( '<strong> Attention: </strong>You are using Klarna Checkout which completely takes over the checkout. To have best experience with WooFunnels Checkout please activate <a href="https://wordpress.org/plugins/klarna-payments-for-woocommerce/" target="_blank">Klarna Payments</a>.', 'woofunnels-aero-checkout' );
				?>
            </p>
        </div>
		<?php
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_Klarna_checkout(), 'klarna_checkout' );
