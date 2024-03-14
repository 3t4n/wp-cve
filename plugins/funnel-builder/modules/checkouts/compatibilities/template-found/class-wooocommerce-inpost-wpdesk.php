<?php

/**
 * WooCommerce InPost By WP Desk
 *
 */
#[AllowDynamicProperties] 

  class WFACP_Shipping_Packzkomaty_impost {
	public function __construct() {
		add_action( 'wfacp_after_template_found', [ $this, 'remove_action' ] );
		add_action( 'wfacp_internal_css', [ $this, 'add_internal_css' ] );

		add_action( 'wfacp_before_shipping_calculator_field', [ $this, 'add_open' ] );
		add_action( 'wfacp_after_shipping_calculator_field', [ $this, 'close_open' ] );

	}

	public function is_enabled() {
		if ( ! class_exists( 'WPDesk_Paczkomaty_Checkout' ) ) {
			return false;
		}

		return true;
	}

	public function add_open(){
		echo "<div id=wfacp_paczkomat_id_wrapper>";
	}
	public function close_open(){
		echo "</div>";
	}

	public function remove_action() {

		if ( ! $this->is_enabled() ) {
			return;
		}
		if ( defined( 'WOOCOMMERCE_PACZKOMATY_INPOST_VERSION' ) && version_compare( WOOCOMMERCE_PACZKOMATY_INPOST_VERSION, '4.0.0', '>' ) ) {
			add_action( 'wpdesk_paczkomaty_checkout_display_shipping_rate', '__return_true' );
		}

		$instance = WFACP_Common::remove_actions( 'woocommerce_review_order_after_shipping', 'WPDesk_Paczkomaty_Checkout', 'woocommerce_review_order_after_shipping' );
		if ( ! is_null( $instance ) && $instance instanceof WPDesk_Paczkomaty_Checkout && method_exists( $instance, 'woocommerce_review_order_after_shipping' ) ) {
			add_action( 'wfacp_woocommerce_review_order_after_shipping', array( $instance, 'woocommerce_review_order_after_shipping' ) );
		}


		$instance_obj = WFACP_Common::remove_actions( 'woocommerce_review_order_after_shipping', 'WPDesk_Paczkomaty_Checkout', 'display_choose_machine_field' );
		if ( ! is_null( $instance_obj ) && $instance_obj instanceof WPDesk_Paczkomaty_Checkout && method_exists( $instance_obj, 'display_choose_machine_field' ) ) {
			add_action( 'wfacp_woocommerce_review_order_after_shipping', array( $instance_obj, 'display_choose_machine_field' ) );

		}

		$instance_obj = WFACP_Common::remove_actions( 'woocommerce_review_order_after_shipping', 'WPDesk_Paczkomaty_Checkout', 'display_choose_machine_field_after_shipping' );
		if ( ! is_null( $instance_obj ) && $instance_obj instanceof WPDesk_Paczkomaty_Checkout && method_exists( $instance_obj, 'display_choose_machine_field_after_shipping' ) ) {
			add_action( 'wfacp_woocommerce_review_order_after_shipping', array( $instance_obj, 'display_choose_machine_field_after_shipping' ) );

		}
	}

	public function add_internal_css() {
		if ( ! $this->is_enabled() || ! function_exists( 'wfacp_template' ) ) {
			return;
		}


		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}
		$bodyClass = "body ";


		if ( 'pre_built' !== $instance->get_template_type() ) {

			$bodyClass = "body #wfacp-e-form ";
		}

		echo "<style>";
		echo $bodyClass . '#wfacp_checkout_form .paczkomaty-shipping select{width: 100%;margin-bottom:10px;}';
		echo $bodyClass . '#wfacp_checkout_form .paczkomaty-shipping .select2-container{width: 100% !important;}';
		echo $bodyClass . '#wfacp_checkout_form .paczkomaty-shipping {border-bottom: 0;}';
		echo $bodyClass . '#wfacp_checkout_form #open-geowidget{display: inline-block;margin-top: 10px;}';
		echo $bodyClass . '#wfacp_checkout_form #paczkomat_id_wrapper .select2-selection__rendered{    padding: 12px;}';
		echo $bodyClass . '#wfacp_checkout_form a#open-geowidget {width: auto;background: #e9e9e9;color: #565656;margin: 0;height: auto;display: inline-block;min-height: 1px;}';


		echo "</style>";

	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Shipping_Packzkomaty_impost(), 'wc-inpost' );

