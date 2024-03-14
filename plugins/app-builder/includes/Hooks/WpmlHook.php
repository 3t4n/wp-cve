<?php

/**
 * class WpmlHook
 *
 * @link       https://appcheap.io
 * @author     ngocdt
 * @since      1.2.0
 *
 */

namespace AppBuilder\Hooks;

defined( 'ABSPATH' ) || exit;

class WpmlHook {

	public function __construct() {
		if ( function_exists( 'WC' ) && ! is_admin() && ! defined( 'DOING_CRON' ) ) {
			add_filter( 'wcml_client_currency', array( $this, 'modify_client_currency' ) );
		}

		if ( function_exists( 'WC' ) && WC()->is_rest_api_request() ) {
			add_filter( 'woocommerce_get_variation_prices_hash', array(
				$this,
				'woocommerce_get_variation_prices_hash'
			), 10, 3 );
		}
	}

	/**
	 * Update hash price
	 *
	 * @param $price_hash
	 * @param $product
	 * @param $for_display
	 *
	 * @return mixed
	 */
	public function woocommerce_get_variation_prices_hash( $price_hash, $product, $for_display ) {
		$data = $price_hash;
		if ( isset( $_GET['currency'] ) && ! empty( $_GET['currency'] ) ) {
			$data['lang'] = $_GET['currency'];
		}

		return $data;
	}

	/**
	 *
	 * Set Currency with URL-Parameter
	 * https://wpml.org/forums/topic/set-currency-with-url-parameter/#post-2688667
	 *
	 * @param $currency
	 *
	 * @return string|void
	 */
	public function modify_client_currency( $currency ) {

		global $woocommerce_wpml;

		if ( isset( $_GET['currency'] ) ) {
			$defined_currencies = $woocommerce_wpml->settings['currency_options'];
			if ( array_key_exists( $_GET['currency'], $defined_currencies ) ) {
				return $_GET['currency'];
			}
		}

		return $currency;

	}
}
