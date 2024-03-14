<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//WooCommerce EU Vat Number Compatibility
class WC_Szamlazz_WooCommerce_EU_Vat_Number_Compatibility {

	public static function init() {

		//Show VAT number on invoice
		add_filter( 'wc_szamlazz_xml_adoszam_eu', array( __CLASS__, 'add_eu_vat_number' ), 10, 2 );

		//For conditional logic, change the company order type condition to match with the EU Vat Number too
		add_filter('wc_szamlazz_vat_overrides_conditions_values', array( __CLASS__, 'get_order_details' ), 10, 2 );
		add_filter('wc_szamlazz_notes_conditions_values', array( __CLASS__, 'get_order_details' ), 10, 2 );
		add_filter('wc_szamlazz_automations_conditions_values', array( __CLASS__, 'get_order_details' ), 10, 2 );
		add_filter('wc_szamlazz_advanced_options_conditions_values', array( __CLASS__, 'get_order_details' ), 10, 2 );

		//Fix if the tax exempt function is iused
		add_action('wc_szamlazz_after_set_vat_exempt', array( __CLASS__, 'maybe_set_vat_exempt'));

		//Hide the field if Hungary is selected
		add_filter( 'woocommerce_eu_vat_number_country_codes', array( __CLASS__, 'hide_field_for_hu') );

		//Validate the VAT number on checkout in toggle mode
		add_action( 'woocommerce_after_checkout_validation', array( __CLASS__, 'vat_number_validate' ), 10, 2);

	}

	public static function add_eu_vat_number( $adoszam_eu, $order ) {

		//EU VAT Assistant
		if(function_exists('wc_eu_vat_get_vat_from_order')) {
			$adoszam_eu = wc_eu_vat_get_vat_from_order($order);
		} else {
			$adoszam_eu = $order->get_meta( '_vat_number' );
		}

		return $adoszam_eu;
	}

	public static function get_order_details( $order_details, $order ) {

		//More accurate order type if its in EU and has a VAT number
		if($order_details['billing_address'] == 'eu' && $order_details['type'] == 'company') {

			//Try to get VAT number
			$adoszam_eu = false;
			if(function_exists('wc_eu_vat_get_vat_from_order')) {
				$adoszam_eu = wc_eu_vat_get_vat_from_order($order);
			} else {
				$adoszam_eu = $order->get_meta( '_vat_number' );
			}

			//If we don't have a VAT number, change it to individual order type
			if(!$adoszam_eu) {
				$order_details['type'] = 'individual';
			}

		}

		return $order_details;
	}

	public static function maybe_set_vat_exempt($data) {
		if(!isset($data['billing_vat_number'])) return;
		$eu_countries = WC()->countries->get_european_union_countries('eu_vat');
		$billing_vat_number = wc_clean( $data['billing_vat_number'] );
		$billing_country    = wc_clean( $data['billing_country'] );
		$shipping_country   = wc_clean( ! empty( $data['shipping_country'] ) && ! empty( $data['ship_to_different_address'] ) ? $data['shipping_country'] : $data['billing_country'] );

		if ( in_array( $billing_country, $eu_countries ) && ! empty( $billing_vat_number ) ) {
			$valid = WC_EU_VAT_Number::vat_number_is_valid( $billing_vat_number, $billing_country );
			if ( !is_wp_error( $valid ) ) {
				WC_EU_VAT_Number::maybe_set_vat_exempt( true, $billing_country, $shipping_country );
			}
		}
	}

	public static function hide_field_for_hu($vat_countries) {
		$display_vat = array_diff($vat_countries, ['HU']);
		return array_values($display_vat);
	}

	public static function vat_number_validate($fields, $errors) {
		$ui_type = (WC_Szamlazz()->get_option('vat_number_always_show', 'no') == 'yes') ? 'show' : 'default';
		$ui_type = WC_Szamlazz()->get_option('vat_number_type', $ui_type);
		$eu_countries = WC()->countries->get_european_union_countries('eu_vat');

		if(
			$fields['billing_country'] != 'HU' &&
			$ui_type == 'toggle' &&
			in_array( $fields['billing_country'], $eu_countries ) &&
			$fields['wc_szamlazz_company_toggle'] &&
			(!$fields['billing_company'] || !$fields['billing_vat_number'])
		) {
			$errors->add( 'validation', apply_filters('wc_szamlazz_company_billing_validation_required_message', esc_html__( 'If you choose company billing, please enter both your company name and VAT number.', 'wc-szamlazz'), $fields) );
		}
	}

}

WC_Szamlazz_WooCommerce_EU_Vat_Number_Compatibility::init();
