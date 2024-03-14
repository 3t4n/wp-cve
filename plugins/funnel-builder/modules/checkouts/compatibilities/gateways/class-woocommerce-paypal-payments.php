<?php

/**
 * WooCommerce PayPal Payments by WooCommerce V 1.9.3
 * Plugin URI: https://woocommerce.com/products/woocommerce-paypal-payments/
 */

#[AllowDynamicProperties] 

  class WFACP_Woocommerce_Paypal_Payments {
	protected $placeorder_back_button_text = '';

	public function __construct() {

		add_action( 'wfacp_after_template_found', [ $this, 'action' ] );
		add_action( 'wfacp_internal_css', [ $this, 'add_internal_css' ] );
		add_filter( 'wfacp_back_link_text', function ( $html ) {

			$this->placeorder_back_button_text = $html;

			return '';
		} );


	}

	public function action() {
		add_filter( 'woocommerce_paypal_payments_checkout_dcc_renderer_hook', function () {

			return 'wfacp_woocommerce_review_order_after_submit';
		} );

		add_action( 'woocommerce_review_order_after_payment', function () {
			if ( ! empty( $this->placeorder_back_button_text ) ) {
				echo $this->placeorder_back_button_text;
			}

		}, 9999 );


	}

	public function add_internal_css() {

		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}
		$bodyClass = "body ";

		if ( 'pre_built' !== $instance->get_template_type() ) {
			$bodyClass = "body #wfacp-e-form ";
		}

		echo "<style>";
		echo $bodyClass . '#wfacp_checkout_form .ppcp-dcc-order-button{float: none;}';
		echo '.wfacp_main_wrapper.right #ppcp-hosted-fields .button {float: right;}';
		echo ".wfacp_main_wrapper.right #ppcp-hosted-fields:after,.wfacp_main_wrapper.right #ppcp-hosted-fields:before {display: block;content: '';}";
		echo '.wfacp_main_wrapper.right #ppcp-hosted-fields:after {clear: both;}';
		echo "</style>";
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Woocommerce_Paypal_Payments(), 'woocommerce-paypal-payments' );