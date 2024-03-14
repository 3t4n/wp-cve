<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * F4 Shipping Phone and E-Mail for WooCommerce | By FAKTOR VIER |
 * Class WFACP_Compatibility_With_F4_shipping_email_phone_wc
 */
#[AllowDynamicProperties]

  class WFACP_Compatibility_With_F4_shipping_email_phone_wc {

	protected static $settings = array(
		'phone_field_enabled' => 'billing',
		'email_field_enabled' => 'billing'
	);

	public function __construct() {

		if ( WFACP_Common::is_funnel_builder_3() ) {
			add_action( 'wffn_rest_checkout_form_actions', [ $this, 'setup_fields_shipping' ] );
		} else {
			add_action( 'init', [ $this, 'setup_fields_shipping' ], 20 );
		}
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'actions' ] );
	}

	public function actions() {

		if ( class_exists( 'F4\WCSPE\Core\Hooks' ) ) {
			return true;
		}
		remove_filter( 'woocommerce_checkout_fields', 'F4\WCSPE\Core\Hooks::add_checkout_shipping_fields' );
		remove_filter( 'woocommerce_shipping_fields', 'F4\WCSPE\Core\Hooks::add_address_shipping_fields' );

	}

	public function is_enabled() {

		if ( class_exists( 'F4\WCSPE\Core\Hooks' ) ) {
			return true;
		}

		return false;
	}


	public function setup_fields_shipping() {

		if ( false == $this->is_enabled() ) {
			return;
		}
		self::$settings = apply_filters( 'f4_setting', array(
			'phone_field_enabled' => get_option( 'woocommerce_enable_shipping_field_phone', 'billing' ),
			'email_field_enabled' => get_option( 'woocommerce_enable_shipping_field_email', 'billing' ),
		) );


		new WFACP_Add_Address_Field( 'phone', array(
			'label'        => __( 'Phone', 'woocommerce' ),
			'required'     => true,
			'type'         => 'tel',
			'class'        => array( 'form-row-wide' ),
			'validate'     => array( 'phone' ),
			'autocomplete' => 'tel',
			'priority'     => 100,
		), 'shipping' );
		new WFACP_Add_Address_Field( 'email', array(
			'label'        => __( 'Email address', 'woocommerce' ),
			'required'     => true,
			'type'         => 'email',
			'class'        => array( 'form-row-wide' ),
			'validate'     => array( 'email' ),
			'autocomplete' => 'tel',
			'priority'     => 110
		), 'shipping' );


	}


	public function validation_fields() {
		add_filter( 'wfacp_checkout_fields', [ $this, 'make_validation' ] );
	}

	public function make_validation( $template_fields ) {

		if ( ! $this->is_enabled() ) {
			return $template_fields;
		}

		$obj             = WFACP_Common::remove_actions( 'woocommerce_billing_fields', 'Woocommerce_PostNL_Postcode_Fields', 'nl_billing_fields' );
		$billing_country = WC()->checkout()->get_value( 'billing_country' );


		if ( isset( $template_fields['billing'] ) ) {
			$required = false;
			if ( $obj instanceof Woocommerce_PostNL_Postcode_Fields && ! empty( $obj ) ) {
				$required = ( $billing_country == 'NL' || $billing_country == 'BE' ) ? true : false;
			}

			$form = 'billing';
			if ( isset( $template_fields['billing'][ $form . '_street_name' ] ) ) {
				$template_fields['billing'][ $form . '_street_name' ]['required'] = $required;
			}

			if ( isset( $template_fields['billing'][ $form . '_house_number' ] ) ) {
				$template_fields['billing'][ $form . '_house_number' ]['required'] = $required;
			}
		}

		$shipping_country = WC()->checkout()->get_value( 'shipping_country' );


		if ( isset( $template_fields['shipping'] ) ) {
			$required = false;
			if ( $obj instanceof Woocommerce_PostNL_Postcode_Fields && ! empty( $obj ) ) {
				$required = ( $shipping_country == 'NL' || $shipping_country == 'BE' ) ? true : false;
			}

			$form = 'shipping';
			if ( isset( $template_fields[ $form ][ $form . '_street_name' ] ) ) {
				$template_fields[ $form ][ $form . '_street_name' ]['required'] = $required;
			}

			if ( isset( $template_fields[ $form ][ $form . '_house_number' ] ) ) {
				$template_fields[ $form ][ $form . '_house_number' ]['required'] = $required;
			}
		}

		return $template_fields;
	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_F4_shipping_email_phone_wc(), 'f4-woocommerce-shipping-phone-and-e-mail' );
