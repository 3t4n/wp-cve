<?php

/**
 * #[AllowDynamicProperties] 

  class WFACP_Advanced_dynamic_Pricing
 * By AlgolPlus
 */
#[AllowDynamicProperties] 

  class WFACP_Advanced_dynamic_Pricing {
	/**
	 * @var WDP_Frontend
	 */
	private $instance = null;

	public function __construct() {
		add_action( 'woocommerce_checkout_update_order_review', [ $this, 'woocommerce_checkout_update_order_review' ], 101 );
		add_filter( 'wdp_rewrite_process_cart_call', [ $this, 'disable_execution' ] );
		add_action( 'wfacp_get_fragments', [ $this, 'actions' ] );
	}

	public function actions() {
		$checkout_override = WFACP_Core()->public->is_checkout_override();
		if ( $checkout_override ) {
			//dedicated
			$this->instance = WFACP_Common::remove_actions( 'wp_loaded', 'WDP_Frontend', 'wp_loaded_process_cart' );
			if ( ! is_null( $this->instance ) ) {
				$this->instance->process_cart( true );
			}
		}
	}

	public function disable_execution( $status ) {
		$checkout_override = WFACP_Core()->public->is_checkout_override();
		if ( false == $checkout_override ) {
			$status = false;
		}

		return $status;
	}

	public function woocommerce_checkout_update_order_review() {
		$checkout_override = WFACP_Core()->public->is_checkout_override();
		if ( false == $checkout_override ) {
			WFACP_Common::remove_actions( 'woocommerce_before_data_object_save', 'WDP_Frontend', 'process_cart' );
		}
	}

}
WFACP_Plugin_Compatibilities::register( new WFACP_Advanced_dynamic_Pricing(), 'algol_plus' );


