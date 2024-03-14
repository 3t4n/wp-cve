<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//Translatepress Compatibility
class WC_Szamlazz_Translatepress_Compatibility {

	public static function init() {
		add_filter( 'wc_szamlazz_xml', array( __CLASS__, 'change_language' ), 10, 2 );
		add_filter( 'wc_szamlazz_invoice_line_item', array( __CLASS__, 'change_item_language'), 10, 4);
		add_filter( 'wc_szamlazz_get_order_language', array( __CLASS__, 'get_language'), 10, 2);
		add_action( 'wc_szamlazz_before_generate_invoice', array( __CLASS__, 'set_language_temporarily'), 10, 2);
	}

	public static function change_language( $invoice, $order ) {
		if($locale = $order->get_meta('trp_language')) {
			$locale = substr($locale, 0, 2);
			if($locale && in_array($locale, array('hu', 'de', 'en', 'it', 'fr', 'hr', 'ro', 'sk', 'es', 'pl', 'cz'))) {
				$invoice->fejlec->szamlaNyelve = $locale;
			}

			$invoice->fejlec->fizmod = htmlspecialchars_decode(wp_strip_all_tags(trp_translate($invoice->fejlec->fizmod)));
		}

		return $invoice;
	}

	public static function change_item_language($tetel, $order_item, $order, $szamla) {
		if($order->get_meta('trp_language')) {
			$tetel->megnevezes = htmlspecialchars_decode(wp_strip_all_tags(trp_translate($tetel->megnevezes)));
		}
		return $tetel;
	}

	public static function get_language($lang_code, $order) {
		if($locale = $order->get_meta('trp_language')) {
			$locale = substr($locale, 0, 2);
			if($locale && in_array($locale, array('hu', 'de', 'en', 'it', 'fr', 'hr', 'ro', 'sk', 'es', 'pl', 'cz'))) {
				$lang_code = $locale;
			}
		}
		return $lang_code;
	}

	public static function set_language_temporarily($order_id) {
		$order = wc_get_order($order_id);
		if($order->get_meta('trp_language')) {
			$locale = $order->get_meta('trp_language');
			trp_switch_language($locale);
		}
	}

}

WC_Szamlazz_Translatepress_Compatibility::init();
