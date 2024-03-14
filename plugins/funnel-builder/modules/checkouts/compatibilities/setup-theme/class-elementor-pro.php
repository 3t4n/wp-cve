<?php


if ( defined( 'ELEMENTOR_VERSION' ) && ! class_exists( 'WFACP_Compatibility_With_Elementor_Pro' ) ) {


	#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Elementor_Pro {
		public function __construct() {

			add_action( 'woocommerce_checkout_update_order_review', [ $this, 'remove_actions' ], - 5 );
			add_action( 'woocommerce_before_calculate_totals', [ $this, 'remove_actions' ], - 5 );
		}

		public function remove_actions( $status ) {
			if ( ! isset( $_GET['wfacp_is_checkout_override'] ) ) {
				return $status;
			}


			if ( ! class_exists( '\ElementorPro\Modules\Woocommerce\Module' ) ) {

				return $status;
			}
			$instance = ElementorPro\Modules\Woocommerce\Module::instance();
			if ( is_null( $instance ) ) {
				return $status;
			}
			remove_action( 'woocommerce_checkout_update_order_review', [ $instance, 'load_widget_before_wc_ajax' ] );
			remove_action( 'woocommerce_before_calculate_totals', [ $instance, 'load_widget_before_wc_ajax' ] );

			return $status;

		}
	}

	WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Elementor_Pro(), 'elementor_pro' );
}