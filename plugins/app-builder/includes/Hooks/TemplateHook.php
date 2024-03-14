<?php

/**
 * class TemplateHook
 *
 * @link       https://appcheap.io
 * @author     ngocdt
 * @since      1.4.0
 *
 */

namespace AppBuilder\Hooks;

use AppBuilder\Utils;

defined( 'ABSPATH' ) || exit;

class TemplateHook {

	public function __construct() {
		add_filter( 'app_builder_prepare_settings_data', array( $this, 'prepare_rest_settings_data' ) );
		add_action( 'save_post_app_builder_template', array( $this, 'save_post_app_builder_template' ), 10, 3 );
	}

	/**
	 *
	 * Pre rest setting data
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function prepare_rest_settings_data( array $data = [] ): array {
		global $woocommerce_wpml;

		// Language
		$languages = apply_filters( 'wpml_active_languages', array(), 'orderby=id&order=desc' );
		$language  = apply_filters( 'wpml_default_language', substr( get_locale(), 0, 2 ) );

		// Currency
		$currencies = array();
		$currency   = function_exists( 'get_woocommerce_currency' ) ? get_woocommerce_currency() : 'USD';

		// Active currency
		$currencyActive = [
			'currency' => 'USD',
			'symbol' => '$',
			'position' => 'left',
			'thousand_sep' => '',
			'decimal_sep' => '.',
			'num_decimals' => 2,
			'rate' => 1,
		];
		if ( function_exists( 'get_woocommerce_currency' ) ) {
			$currencyActive = array(
				'currency'     => get_woocommerce_currency(),
				'symbol'       => html_entity_decode( get_woocommerce_currency_symbol() ),
				'position'     => get_option( 'woocommerce_currency_pos' ),
				'thousand_sep' => wc_get_price_thousand_separator(),
				'decimal_sep'  => wc_get_price_decimal_separator(),
				'num_decimals' => wc_get_price_decimals(),
				'rate'         => 1,
			);
		}

		if ( ! empty( $woocommerce_wpml->multi_currency ) && ! empty( $woocommerce_wpml->settings['currencies_order'] ) ) {
			$currencies = $woocommerce_wpml->multi_currency->get_currencies( 'include_default = true' );
			foreach ( $currencies as $key => $value ) {
				if ( $key == $currency ) {
					$currencies[ $currency ] = $currencyActive;
				} else {
					$value['symbol']    = html_entity_decode( get_woocommerce_currency_symbol( $key ) );
					$currencies[ $key ] = $value;
				}
			}
		} else if ( function_exists( 'WC_Payments_Multi_Currency' ) ) {
			/**
			 * Support enabled WC Payments Multi Currency
			 */
			$currencies = WC_Payments_Multi_Currency()->get_enabled_currencies();
			foreach ( $currencies as $key => $value ) {
				if ( $key == $currency ) {
					$currencies[ $currency ] = $currencyActive;
				} else {
					$currencies[ $key ] = array(
						'currency'     => $key,
						'symbol'       => html_entity_decode( $value->get_symbol() ),
						'position'     => get_option( 'woocommerce_currency_pos' ),
						'thousand_sep' => wc_get_price_thousand_separator(),
						'decimal_sep'  => wc_get_price_decimal_separator(),
						'num_decimals' => wc_get_price_decimals(),
						'rate'         => $value->get_rate(),
					);
				}
			}

		} else if ( class_exists( '\WOOMULTI_CURRENCY_F_Data' ) || class_exists( '\WOOMULTI_CURRENCY_Data' ) ) {
			$woo_multi_currency = class_exists( '\WOOMULTI_CURRENCY_F_Data' )
				? new \WOOMULTI_CURRENCY_F_Data() : new \WOOMULTI_CURRENCY_Data();
			$list_currencies    = $woo_multi_currency->get_list_currencies();
			foreach ( $list_currencies as $key => $value ) {
				if ( $key == $currency ) {
					$currencies[ $currency ] = $currencyActive;
				} else {

					$decimals = (int) $value['decimals'];
					$symbol   = $value['custom'];
					$symbol   = $symbol ?: get_woocommerce_currency_symbol( $key );

					$currencies[ $key ] = array(
						'currency'     => $key,
						'symbol'       => html_entity_decode($symbol),
						'position'     => get_option( 'woocommerce_currency_pos' ),
						'thousand_sep' => wc_get_price_thousand_separator(),
						'decimal_sep'  => wc_get_price_decimal_separator(),
						'num_decimals' => $decimals,
						'rate'         => $value['rate'],
					);
				}
			}
		} else {
			$currencies[ $currency ] = $currencyActive;
		}

		if ( class_exists( 'WC_Payments_Multi_Currency' ) ) {
			$currencies = WC_Payments_Multi_Currency()->get_enabled_currencies();
		}

		// Used custom currencies
		if ( defined( 'APP_BUILDER_CURRENCIES' ) ) {
			$currencies = unserialize( APP_BUILDER_CURRENCIES );
		}

		if ( count( $languages ) == 0 ) {
			// Used custom languages
			if ( defined( 'APP_BUILDER_LANGUAGES' ) ) {
				$languages = unserialize( APP_BUILDER_LANGUAGES );
			} else {
				$languages[ $language ] = array(
					'code'        => $language,
					'native_name' => $language,
				);
			}
		}

		return array_merge( array(
			'cart_url'          => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '',
			'checkout_url'      => function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '',
			'store'             => Utils::vendorActive(),
			'language'          => $language ?: 'en',
			'languages'         => $languages,
			'currencies'        => $currencies,
			'currency'          => $currency,
			'placeholder'       => APP_BUILDER_ASSETS . DIRECTORY_SEPARATOR . 'images/placeholder-416x416.png',
			'placeholder_black' => APP_BUILDER_ASSETS . DIRECTORY_SEPARATOR . 'images/placeholder-black-416x416.png',
		), $data );
	}

	/** Action fires once a template has been saved.
	 *
	 * @param $post_id
	 * @param $post
	 * @param $update
	 */
	public function save_post_app_builder_template( $post_id, $post, $update ) {
		wp_cache_delete( "settings-$post_id", 'app-builder' );
	}
}
