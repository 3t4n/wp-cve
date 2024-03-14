<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties]

  class WFACP_Compatibility_With_Conversio {
	public function __construct() {
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_fields' ] );
		add_action( 'wfacp_internal_css', [ $this, 'actions' ] );
	}

	public function actions() {
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );
		add_filter( 'woocommerce_form_field', [ $this, 'display_field_conditional' ], 10, 2 );
	}

	public function add_fields( $field ) {
		if ( $this->is_enable() ) {
			$field['billing_email_conversio_optin'] = [
				'type'          => 'checkbox',
				'default'       => true,
				'label'         => __( 'Conversio', 'receiptful-for-woocommerce' ),
				'validate'      => [],
				'id'            => 'billing_email_conversio_optin',
				'required'      => false,
				'wrapper_class' => [],
			];

		}

		return $field;
	}

	public function is_enable() {
		if ( class_exists( 'Conversio_Front_End' ) ) {
			$optin = get_option( 'receiptful_marketing_optin', 'unchecked' );
			if ( $optin !== 'disabled' ) {
				return true;
			}
		}

		return false;
	}

	public function add_default_wfacp_styling( $args, $key ) {
		if ( $key == 'billing_email_conversio_optin' && $this->is_enable() ) {
			$optin_text = get_option( 'receiptful_marketing_optin_text', __( 'Subscribe to marketing emails?', 'receiptful-for-woocommerce' ) );
			if ( '' !== $optin_text ) {
				$args['label'] = $optin_text;
			}
			$optin = get_option( 'receiptful_marketing_optin', 'unchecked' );
			if ( 'checked' === $optin ) {
				$args['default'] = ( 'checked' === $optin );
			}
		}

		return $args;
	}

	/**
	 * display conversio field if user not optin earlier
	 *
	 * @param $field
	 * @param $key
	 *
	 * @return string
	 */
	public function display_field_conditional( $field, $key ) {
		if ( $key == 'billing_email_conversio_optin' && $this->is_enable() ) {
			if ( WC()->customer && WC()->customer->get_meta( 'accepts_conversio_marketing' ) == true ) {
				return '';
			}
		}

		return $field;

	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Conversio(), 'conversio' );
