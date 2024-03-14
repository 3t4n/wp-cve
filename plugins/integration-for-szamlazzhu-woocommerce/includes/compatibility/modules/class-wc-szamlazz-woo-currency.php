<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//WooCommerce Currency Switcher by WooBeWoo Compatibility
class WC_Szamlazz_Woo_Currency_Compatibility {

	public static function init() {
		add_filter( 'wc_szamlazz_invoice_line_item', array( __CLASS__, 'change_currency_if_needed' ), 10, 4 );
	}

	public static function change_currency_if_needed($tetel, $order_item, $order, $szamla) {
		if($tetel->nettoEgysegar) $tetel->nettoEgysegar = self::convert_currency(floatval($tetel->nettoEgysegar), $order);
		if($tetel->nettoErtek) $tetel->nettoErtek = self::convert_currency(floatval($tetel->nettoErtek), $order);
		if($tetel->afaErtek) $tetel->afaErtek = self::convert_currency(floatval($tetel->afaErtek), $order);
		if($tetel->bruttoErtek) $tetel->bruttoErtek = self::convert_currency(floatval($tetel->bruttoErtek), $order);
		return $tetel;
	}

	public static function convert_currency($price, $order) {
		$currencies = frameWcu::_()->getModule( 'currency' )->getModel()->getCurrencies();
		$defaultCurrency = frameWcu::_()->getModule( 'currency' )->getDefaultCurrency();
		$currentCurrency = $order->get_currency();
		$exchangeFee = isset($currencies[$currentCurrency]['exchange_fee']) ? $currencies[$currentCurrency]['exchange_fee'] : 0;
		$cryptoCurrencies = frameWcu::_()->getModule( 'currency' )->getCryptoCurrencyList();
		$decimalSep = frameWcu::_()->getModule( 'currency' )->decimalSep;
		$priceNumDecimals = frameWcu::_()->getModule( 'currency' )->priceNumDecimals;
		$precision = frameWcu::_()->getModule( 'currency' )->getModel()->_getPriceDecimalsCount($currentCurrency, $priceNumDecimals, $currencies);

		//Rewrite manual rate
		if ( !empty($currencies[$currentCurrency]['rate_custom']) ) {
			$currencies[$currentCurrency]['rate'] = $currencies[$currentCurrency]['rate_custom'];
		} elseif ( $exchangeFee ) {
			$exchangeFee = $exchangeFeeSign ? $exchangeFee * (-1) : $exchangeFee;
			$currencies[$currentCurrency]['rate'] += $exchangeFee;
		}

		//Edited this line to set default converting of currency
		if ( !array_key_exists( $currentCurrency, $cryptoCurrencies ) ) {
		$price = isset($currencies[$currentCurrency]) && $currencies[$currentCurrency] != null
			? number_format(floatval((float) $price * (float) $currencies[$currentCurrency]['rate']), $precision, $decimalSep, '')
			: number_format(floatval((float) $price * (float) $currencies[$defaultCurrency]['rate']), $precision, $decimalSep, '');
		} else {
			$price = isset($currencies[$currentCurrency]) && $currencies[$currentCurrency] != null
			? floatval((float) $price * (float) $currencies[$currentCurrency]['rate'])
			: floatval((float) $price * (float) $currencies[$currentCurrency]['rate']);
		}

		return $price;
	}

}

WC_Szamlazz_Woo_Currency_Compatibility::init();
