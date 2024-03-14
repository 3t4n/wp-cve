<?php

/**
 * WooCommerce Braintree Gateway
 * @author    WooCommerce
 * http://docs.woocommerce.com/document/braintree/
 * #[AllowDynamicProperties] 

  class WFACP_WC_Braintree_Compatibility
 */
#[AllowDynamicProperties] 

  class WFACP_WC_Braintree_Compatibility {
	private $instance = null;
	private $page_settings = null;

	public function __construct() {
		$apple_pay_enabled = get_option( 'sv_wc_apple_pay_enabled', 'no' );
		if ( 'yes' !== $apple_pay_enabled ) {
			return;
		}
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'register_action' ] );
		add_filter( 'wfacp_smart_buttons', [ $this, 'add_buttons' ], 16 );
		add_action( 'wfacp_smart_button_container_wc_braintree', [ $this, 'print_smart_buttons' ] );
	}

	public function register_action() {
		$this->page_settings = WFACP_Common::get_page_settings( WFACP_Common::get_id() );
		if ( ! wc_string_to_bool( $this->page_settings['enable_smart_buttons'] ) ) {
			return;
		}
		if ( class_exists( 'WC_Checkout_Add_Ons_Loader' ) ) {
			$this->instance = WFACP_Common::remove_actions( 'woocommerce_review_order_before_payment', 'WC_Braintree\Apple_Pay\Frontend', 'maybe_render_external_checkout' );
		} else {
			$this->instance = WFACP_Common::remove_actions( 'woocommerce_before_checkout_form', 'WC_Braintree\Apple_Pay\Frontend', 'maybe_render_external_checkout_with_divider' );
		}
		if ( is_null( $this->instance ) ) {
			$this->instance = WFACP_Common::remove_actions( 'sv_wc_external_checkout_with_divider', 'WC_Braintree\Apple_Pay\Frontend', 'render_external_checkout_with_divider' );
		}

	}

	public function add_buttons( $buttons ) {
		$this->page_settings = WFACP_Common::get_page_settings( WFACP_Common::get_id() );
		if ( ! wc_string_to_bool( $this->page_settings['enable_smart_buttons'] ) ) {
			return $buttons;
		}
		$buttons['wc_braintree'] = [
			'iframe' => true,
			'name'   => __( 'Braintree' ),
		];

		return $buttons;

	}

	public function print_smart_buttons() {
		if ( ! is_null( $this->instance ) && method_exists( $this->instance, 'render_external_checkout' ) ) {
			$this->instance->render_external_checkout();
		}
	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_WC_Braintree_Compatibility(), 'wc-braintree' );


