<?php

/**
 * Multi-currency for WooCommerce
 * By VillaTheme
 * https://wordpress.org/plugins/woo-multi-currency/
 */
if ( ! class_exists( 'BWFAN_Compatibility_With_WC_Multi_Currency_Villatheme' ) ) {
	class BWFAN_Compatibility_With_WC_Multi_Currency_Villatheme {

		public function __construct() {
			add_filter( 'bwfan_ab_cart_total_base', [ $this, 'save_base_price_in_database' ] );
			add_filter( 'bwfan_abandoned_cart_restore_link', [ $this, 'add_currency_parameter_in_url' ], 99, 2 );
		}

		/**
		 * Modify the cart total price
		 *
		 * @param $price
		 *
		 * @return float|string
		 */
		public function save_base_price_in_database( $price ) {
			$setting = false;

			if ( class_exists( 'WOOMULTI_CURRENCY_F_Data' ) ) {
				$setting = new WOOMULTI_CURRENCY_F_Data();
			}
			if ( class_exists( 'WOOMULTI_CURRENCY_Data' ) ) {
				$setting = new WOOMULTI_CURRENCY_Data();
			}

			if ( false === $setting ) {
				return wc_format_decimal( $price, wc_get_price_decimals() );
			}

			$selected_currencies = $setting->get_list_currencies();
			$current_currency    = $setting->get_current_currency();

			if ( isset( $selected_currencies[ $current_currency ] ) ) {
				$price = $price / $selected_currencies[ $current_currency ]['rate'];
			}

			return wc_format_decimal( $price, wc_get_price_decimals() );
		}

		/**
		 * Append currency in the restore link
		 *
		 * @param $url
		 * @param $token
		 *
		 * @return string
		 */
		public function add_currency_parameter_in_url( $url, $token ) {
			global $wpdb;
			$currency = $wpdb->get_var( $wpdb->prepare( "SELECT `currency` FROM {$wpdb->prefix}bwfan_abandonedcarts WHERE `token` = %s", $token ) );

			if ( empty( $currency ) ) {
				return $url;
			}

			$url = add_query_arg( array(
				'wmc-currency' => $currency,
			), $url );

			return $url;
		}
	}

	if ( defined( 'WOOMULTI_CURRENCY_VERSION' ) || defined( 'WOOMULTI_CURRENCY_F_VERSION' ) ) {
		new BWFAN_Compatibility_With_WC_Multi_Currency_Villatheme();
	}
}
