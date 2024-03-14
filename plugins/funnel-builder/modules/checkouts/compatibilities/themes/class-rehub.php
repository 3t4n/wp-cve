<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties]

  class WFACP_Compatibility_With_Rehub {

	public function __construct() {

		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_customer_details' ] );
		add_action( 'wfacp_checkout_page_found', [ $this, 'remove_customer_details' ] );
		add_action( 'init', [ $this, 'register_elementor_widget' ], 150 );
	}

	public function remove_customer_details() {

		remove_action( 'woocommerce_checkout_before_customer_details', 'rehub_woo_before_checkout' );
		remove_action( 'woocommerce_checkout_after_customer_details', 'rehub_woo_average_checkout' );

	}

	public function register_elementor_widget() {
		if ( class_exists( 'Elementor\Plugin' ) ) {
			if ( is_admin() ) {
				return;
			}
			if ( false == wfacp_elementor_edit_mode() ) {
				$instance = WFACP_Elementor::get_instance();
				if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) ) {
					remove_action( 'elementor/widgets/register', [ $instance, 'initialize_widgets' ] );
				} else {
					remove_action( 'elementor/widgets/widgets_registered', [ $instance, 'initialize_widgets' ] );
				}

				$instance->initialize_widgets();
			}
		}
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Rehub(), 'rehub' );
