<?php

/**
 * WP Zasielkovna Shipping by Provis Technologies upto (1.0.0)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Zasielkovna {
	public $instance = null;
	public $plugin_name = null;

	public function __construct() {
		/* checkout page */
		$this->actions();
	}

	public function enable() {
		return class_exists( 'Wp_Zasielkovna_Shipping_Public' );
	}

	public function actions() {
		if ( ! $this->enable() ) {
			return;
		}

		$this->instance = WFACP_Common::remove_actions( 'woocommerce_after_shipping_rate', 'Wp_Zasielkovna_Shipping_Public', 'add_zasielkovna_shipping_options' );
		if ( ! $this->instance instanceof Wp_Zasielkovna_Shipping_Public ) {
			return;
		}
		add_action( 'woocommerce_after_shipping_rate', [ $this, 'add_zasielkovna_shipping_options' ], 30, 2 );
	}

	public function add_zasielkovna_shipping_options( $method, $index ) {
		if ( ! is_checkout() ) {
			return;
		}

		$method_to_display_shipping_options = get_option( 'zasielkovna_shipping_method' );
		if ( $method->id != $method_to_display_shipping_options ) {
			return;
		}

		$selected_method_id = WC()->session->chosen_shipping_methods[ $index ];
		if ( $selected_method_id == $method_to_display_shipping_options && defined( 'WP_ZASIELKOVNA_SHIPPING_PLUGIN_DIR' ) ) {
			include WP_ZASIELKOVNA_SHIPPING_PLUGIN_DIR . "/public/partials/wp-zasielkovna-shipping-public-checkout.php";
		}
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Zasielkovna(), 'wfacp-zasielkovna' );


