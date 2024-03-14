<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties]

  class WFACP_Compatibility_With_Active_Woo {
	public function __construct() {

		add_action( 'wfacp_outside_header', [ $this, 'actions' ] );
		$this->dequeue_js();
		$this->remove_actions();
	}

	public function actions() {
		global $activewoo;
		remove_action( 'woocommerce_before_checkout_form', array( $activewoo->recover_cart, 'print_subscribe_form' ) );
	}

	public function is_enable() {
		return class_exists( 'WC_Active_Woo' );
	}

	public function dequeue_js() {
		if ( ! $this->is_enable() ) {
			return;
		}

		global $activewoo;
		add_action( 'woocommerce_before_checkout_form', function () {
			$status = true;
			if ( class_exists( 'WC_Integration_Active_Woo_Advanced_Recover' ) ) {
				$aw_rc       = new WC_Integration_Active_Woo_Advanced_Recover();
				$rc_settings = $aw_rc->settings;
				$status      = isset( $rc_settings['cart_recover'] ) && ( $rc_settings['cart_recover'] == 'yes' ) ? true : false;
			}
			if ( $status ) {
				wp_enqueue_script( 'aw_rc_cart_js' );
				wp_enqueue_script( 'wfacp_active_woo', WFACP_PLUGIN_URL . '/compatibilities/js/activewoo.min.js', [ 'wfacp_checkout_js' ], WFACP_VERSION, true );
			}
		} );

	}

	public function remove_actions() {
		if ( function_exists( 'G3D_APP' ) && G3D_APP() instanceof G3D_APP ) {
			remove_filter( 'woocommerce_cart_item_thumbnail', array( G3D_APP(), 'cart_item_uses_large_image_link' ), 10 );
		}
	}

}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Active_Woo(), 'activewoo' );

