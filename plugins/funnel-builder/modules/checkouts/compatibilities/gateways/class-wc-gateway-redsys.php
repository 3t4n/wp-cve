<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce Servired/RedSys Spain Gateway  by José Conti (v.17.0.2)
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_WC_Gateway_Redsys {

	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );
		add_action( 'wfacp_internal_css', [ $this, 'add_internal_css' ] );

	}

	public function enable() {

		return class_exists( 'WC_Gateway_Redsys' );
	}

	public function action() {

		if ( ! $this->enable() ) {
			return;
		}

		$this->instance = WFACP_Common::remove_actions( 'woocommerce_checkout_fields', 'WC_Gateway_Redsys', 'override_checkout_fields' );


		add_action( 'woocommerce_checkout_before_customer_details', [ $this, 'add_fields' ] );
	}

	public function add_fields() {

		if ( ! $this->enable() || ! $this->instance instanceof WC_Gateway_Redsys ) {
			return;
		}


		$fields = $this->instance->override_checkout_fields( [] );

		if ( ! is_array( $fields ) || count( $fields ) == 0 ) {
			return;
		}
		echo '<div class=woocommerce-billing-fields__field-wrapper id="wfacp-wc-gateway-redsys">';
		foreach ( $fields as $field_key => $field ) {
			foreach ( $field as $key => $value ) {
				if ( isset( $value['type'] ) && 'hidden' === $value['type'] ) {
					woocommerce_form_field( $key, $value );
				}
			}

		}
		echo '</div>';
	}

	public function add_internal_css() {


		if ( ! $this->enable() ) {
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

		$cssHtml = "<style>";
		$cssHtml .= $bodyClass . "#wfacp-wc-gateway-redsys {clear:both;}";
		$cssHtml .= "</style>";
		echo $cssHtml;

	}


}

new WFACP_Compatibility_With_WC_Gateway_Redsys();

