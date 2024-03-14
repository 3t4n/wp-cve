<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//WooCommerce Checkout Manager Compatibility
class WC_Szamlazz_Checkout_Manager_Compatibility {

	public static function init() {
		add_filter( 'wooccm_billing_fields' , array( __CLASS__, 'add_vat_number_checkout_field' ), 999 );
	}

	public static function add_vat_number_checkout_field($fields) {
		if(WC_Szamlazz()->get_option('vat_number_form', 'no') == 'yes') {
			$fields = WC_Szamlazz_Vat_Number_Field::add_vat_number_checkout_field($fields);
		}
		return $fields;
	}

}

WC_Szamlazz_Checkout_Manager_Compatibility::init();
