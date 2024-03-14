<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//WooCommerce Advanced Quantity Compatibility
class WC_Szamlazz_EU_Vat_Assistant_Compatibility {

	public static function init() {
		add_filter( 'wc_szamlazz_xml_adoszam_eu', array( __CLASS__, 'add_eu_vat_number' ), 10, 2 );
	}

	public static function add_eu_vat_number( $adoszam_eu, $order ) {

		//EU VAT Assistant
		if($order->get_meta( 'vat_number' ) && $order->get_meta('_vat_number_validated') && $order->get_meta('_vat_number_validated') == 'valid') {
			return $order->get_meta( 'vat_number' );
		}

		return $adoszam_eu;
	}

}

WC_Szamlazz_EU_Vat_Assistant_Compatibility::init();
