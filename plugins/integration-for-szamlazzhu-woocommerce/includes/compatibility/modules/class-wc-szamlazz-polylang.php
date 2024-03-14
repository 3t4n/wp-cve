<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//Polylang Compatibility
class WC_Szamlazz_Polylang_Compatibility {

	public static function init() {
		add_filter( 'wc_szamlazz_xml', array( __CLASS__, 'change_language' ), 10, 2 );
		add_filter( 'wc_szamlazz_invoice_line_item', array( __CLASS__, 'change_item_language'), 10, 4);
		add_filter( 'wc_szamlazz_get_order_language', array( __CLASS__, 'get_language'), 10, 2);
	}

	public static function change_language( $invoice, $order ) {
		if($locale = pll_get_post_language($order->get_id())) {
			$locale = substr($locale, 0, 2);
			if($locale && in_array($locale, array('hu', 'de', 'en', 'it', 'fr', 'hr', 'ro', 'sk', 'es', 'pl', 'cz'))) {
				$invoice->fejlec->szamlaNyelve = $locale;
			}

            $invoice->fejlec->fizmod = htmlspecialchars_decode(wp_strip_all_tags(pll_translate_string((string)$invoice->fejlec->fizmod, $locale)));
		}

		return $invoice;
	}

	public static function change_item_language($tetel, $order_item, $order, $szamla) {
		if($locale = pll_get_post_language($order->get_id())) {
			$tetel->megnevezes = htmlspecialchars_decode(wp_strip_all_tags(pll_translate_string((string)$tetel->megnevezes, $locale)));
		}
		return $tetel;
	}

	public static function get_language($lang_code, $order) {
		if($locale = pll_get_post_language($order->get_id())) {
			$locale = substr($locale, 0, 2);
			if($locale && in_array($locale, array('hu', 'de', 'en', 'it', 'fr', 'hr', 'ro', 'sk', 'es', 'pl', 'cz'))) {
				$lang_code = $locale;
			}
		}
		return $lang_code;
	}

}

WC_Szamlazz_Polylang_Compatibility::init();