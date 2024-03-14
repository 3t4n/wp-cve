<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//WooCommerce Advanced Quantity Compatibility
class WC_Szamlazz_HuCommerce_Compatibility {

	public static function init() {
		add_filter( 'wc_szamlazz_xml_adoszam', array( __CLASS__, 'add_vat_number' ), 10, 2 );

		//Notify user that vat number settings enabled in Hucommerce
		add_filter( 'wc_szamlazz_settings_fields', array( __CLASS__, 'vat_number_notify' ) );

	}

	public static function add_vat_number( $adoszam, $order ) {
		$surbma_hc_fields = get_option('surbma_hc_fields');
		if($surbma_hc_fields && $surbma_hc_fields['taxnumber']) {

			//Check for user meta
			if($customer_id = $order->get_customer_id()) {
				if($taxcode = get_user_meta($customer_id, 'billing_tax_number', true)) {
					return $taxcode;
				}
			}

			//Check for order meta
			if($taxcode = $order->get_meta( '_billing_tax_number' )) {
				return $taxcode;
			}

		}

		return $adoszam;
	}

	public static function vat_number_notify($settings) {
		$message = '';
		$surbma_hc_fields = get_option('surbma_hc_fields');
		if($surbma_hc_fields && $surbma_hc_fields['taxnumber']) {
			$message = '<span class="wc-szamlazz-settings-error"><span class="dashicons dashicons-warning"></span> '.__('The VAT number field is already displayed on the checkout page by the HuCommerce extension', 'wc-szamlazz').'</span>';
		}

		$settings['vat_number_form']['description'] = $message;
		return $settings;
	}

}

WC_Szamlazz_HuCommerce_Compatibility::init();
