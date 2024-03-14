<?php
/**
 * WooCommerce All Currencies - Core
 *
 * @version 2.4.2
 * @since   2.0.0
 * @author  Algoritmika Ltd.
 * @author  WP Wham
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_All_Currencies_Core' ) ) :

class Alg_WC_All_Currencies_Core {
	
	public $symbols = null;

	/**
	 * Constructor.
	 *
	 * @version 2.2.1
	 * @todo    [dev] (maybe) rename plugin to "Currencies for WooCommerce"
	 * @todo    [feature] (maybe) virtual currencies
	 */
	function __construct() {
		if ( 'yes' === get_option( 'alg_wc_all_currencies_enabled', 'yes' ) ) {
			add_filter( 'woocommerce_currencies',      array( $this, 'add_all_currencies'),     PHP_INT_MAX );
			add_filter( 'woocommerce_currency_symbol', array( $this, 'change_currency_symbol'), PHP_INT_MAX, 2 );
			$this->symbols = alg_wcac_get_all_currencies_symbols();
			add_shortcode( 'alg_wcac_lang', array( $this, 'language_shortcode' ) );
		}
	}

	/**
	 * language_shortcode.
	 *
	 * @version 2.2.1
	 * @since   2.2.1
	 */
	function language_shortcode( $atts, $content = '' ) {
		// E.g.: `[alg_wcac_lang lang="EN,DE" lang_symbol="$" not_lang_symbol="USD"]`
		if ( isset( $atts['lang_symbol'] ) && isset( $atts['not_lang_symbol'] ) && ! empty( $atts['lang'] ) ) {
			return ( ! defined( 'ICL_LANGUAGE_CODE' ) || ! in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ) ) ) ?
				$atts['not_lang_symbol'] : $atts['lang_symbol'];
		}
		// E.g.: `[alg_wcac_lang lang="EN,DE"]$[/alg_wcac_lang][alg_wcac_lang not_lang="EN,DE"]USD[/alg_wcac_lang]`
		return (
			( ! empty( $atts['lang'] )     && ( ! defined( 'ICL_LANGUAGE_CODE' ) || ! in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ) ) ) ) ||
			( ! empty( $atts['not_lang'] ) &&     defined( 'ICL_LANGUAGE_CODE' ) &&   in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['not_lang'] ) ) ) ) )
		) ? '' : $content;
	}

	/**
	 * get_default_currency_symbol.
	 *
	 * @version 2.1.1
	 * @since   2.1.1
	 */
	function get_default_currency_symbol( $code = '', $default_symbol = '' ) {
		if ( '' != ( $woocommerce_default_symbol = $this->get_original_woocommerce_currency_symbol( $code ) ) ) {
			return $woocommerce_default_symbol;
		} else {
			if ( '' == $default_symbol ) {
				if ( '' == $code ) {
					$code = get_woocommerce_currency();
				}
				if ( isset( $this->symbols[ $code ] ) ) {
					return $this->symbols[ $code ];
				}
			}
			return $default_symbol;
		}
	}

	/**
	 * get_original_woocommerce_currency_symbol.
	 *
	 * @version 2.1.1
	 * @since   2.1.1
	 */
	function get_original_woocommerce_currency_symbol( $code = '' ) {
		remove_filter( 'woocommerce_currency_symbol', array( $this, 'change_currency_symbol'), PHP_INT_MAX, 2 );
		$symbol = get_woocommerce_currency_symbol( $code );
		add_filter(    'woocommerce_currency_symbol', array( $this, 'change_currency_symbol'), PHP_INT_MAX, 2 );
		return $symbol;
	}

	/**
	 * add_all_currencies.
	 *
	 * @version 2.4.0
	 */
	function add_all_currencies( $default_currencies ) {
		// Lists
		$currencies = alg_wcac_get_all_currencies_names();
		foreach( $currencies as $code => $name ) {
			$default_currencies[ $code ] = apply_filters( 'alg_wc_all_currencies_filter', $name, 'value_name', array( 'currency_code' => $code ) );
		}
		// Custom currencies
		if ( 'yes' === get_option( 'alg_wc_all_currencies_custom_currencies_enabled', 'no' ) ) {
			$custom_currencies_codes = get_option( 'alg_wc_all_currencies_custom_currencies_codes', array() );
			$custom_currencies_names = get_option( 'alg_wc_all_currencies_custom_currencies_names', array() );
			for ( $i = 1; $i <= apply_filters( 'alg_wc_all_currencies_filter', 1, 'custom_currencies_total' ); $i++ ) {
				if ( ! empty( $custom_currencies_codes[ $i ] ) ) {
					$default_currencies[ $custom_currencies_codes[ $i ] ] = ( ! empty( $custom_currencies_names[ $i ] ) ? $custom_currencies_names[ $i ] : $custom_currencies_codes[ $i ] );
				}
			}
		}
		// Sort & return
		asort( $default_currencies );
		return $default_currencies;
	}

	/**
	 * change_currency_symbol.
	 *
	 * @version 2.2.0
	 */
	function change_currency_symbol( $currency_symbol, $currency ) {
		// Hide symbol (on frontend)
		if ( 'yes' === get_option( 'alg_wc_all_currencies_hide_symbol', 'no' ) && ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ! is_admin() ) ) {
			return '';
		}
		// Code as symbol
		if ( 'yes' === get_option( 'alg_wc_all_currencies_use_code_as_symbol', 'no' ) ) {
			return $currency;
		}
		// Default symbol
		if ( '' == $currency_symbol && ! empty( $this->symbols[ $currency ] ) ) {
			$currency_symbol = $this->symbols[ $currency ];
		}
		// Final result
		return apply_filters( 'alg_wc_all_currencies_filter', $currency_symbol, 'value_symbol', array( 'currency_code' => $currency ) );
	}
}

endif;

return new Alg_WC_All_Currencies_Core();
