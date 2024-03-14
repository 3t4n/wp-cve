<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Wrapper for deprecated functions so we can apply some extra logic.
 *
 * @param  string  $function
 * @param  string  $version
 * @param  string  $replacement
 *
 * @since  2.2.0
 *
 */
function cbcurrencyconverter_deprecated_function( $function, $version, $replacement = null ) {
	if ( defined( 'DOING_AJAX' ) ) {
		do_action( 'deprecated_function_run', $function, $replacement, $version );
		$log_string = "The {$function} function is deprecated since version {$version}.";
		$log_string .= $replacement ? " Replace with {$replacement}." : '';
		error_log( $log_string );
	} else {
		_deprecated_function( $function, $version, $replacement );
	}
}//end function cbcurrencyconverter_deprecated_function

if ( ! function_exists( 'cbcurrencyconverter_get_rate' ) ) {
	/**
	 * Get currency rate
	 *
	 * @param $price
	 * @param $convertfrom
	 * @param $convertto
	 * @param  int  $decimal_point
	 *
	 * @return mixed|void
	 */
	function cbcurrencyconverter_get_rate( $price, $convertfrom, $convertto, $decimal_point = 2 ) {
		return CBCurrencyConverterHelper::cbcurrencyconverter_get_rate( $price, $convertfrom, $convertto, $decimal_point );
	}//end function cbcurrencyconverter_get_rate
}

if ( ! function_exists( 'cbxcccalcview' ) ):
	/**
	 * Shows the calculator form
	 *
	 * @param  string  $reference  shortcode or widget
	 * @param  array  $instance
	 *
	 * @return string
	 *
	 */
	function cbxcccalcview( $reference = 'shortcode', $instance = [] ) {
		cbcurrencyconverter_deprecated_function( 'cbxcccalcview function', '2.2', 'CBCurrencyConverterHelper::cbxcccalcview' );

		return CBCurrencyConverterHelper::cbxcccalcview( $reference = 'shortcode', $instance );
	}//end function cbxcccalcview
endif;


if ( ! function_exists( 'cbxcclistview' ) ):
	/**
	 * currencly list layout
	 *
	 * @param  string  $reference  shortcode or widget
	 * @param  array  $instance
	 *
	 * @return string
	 */
	function cbxcclistview( $reference = 'shortcode', $instance = [] ) {
		cbcurrencyconverter_deprecated_function( 'cbxcclistview function', '2.2', 'CBCurrencyConverterHelper::cbxcclistview' );

		return CBCurrencyConverterHelper::cbxcclistview( $reference = 'shortcode', $instance );
	}//end function cbxcclistview
endif;

/**
 * Shows the calculator in woocommerce
 *
 * @param  string  $reference
 * @param  array  $instance
 *
 * @return string
 */
if ( ! function_exists( 'cbxcccalcinline' ) ):
	function cbxcccalcinline( $reference = 'shortcode', $instance = [] ) {
		cbcurrencyconverter_deprecated_function( 'cbxcccalcinline function', '2.2', 'CBCurrencyConverterHelper::cbxcccalcinline' );

		return CBCurrencyConverterHelper::cbxcccalcinline( $reference = 'shortcode', $instance );
	}//end function cbxcccalcinline
endif;

if ( ! function_exists( 'cbcurrencyconverter_first_value' ) ) {
	function cbcurrencyconverter_first_value( $arr ) {
		//return array_shift(array_values(&$arr));;

		$first_key = array_key_first( $arr );
		if ( $first_key === false ) {
			return '';
		}

		return $arr[ $first_key ];
	}//end function cbcurrencyconverter_first_value
}

if ( ! function_exists( 'cbcurrencyconverter_get_symbols' ) ) {
	/**
	 * All currency symbols
	 *
	 * @return mixed|null
	 * @since 3.0.9
	 *
	 */
	function cbcurrencyconverter_get_symbols() {
		return CBCurrencyConverterHelper::getCurrencySymbols();
	}//end function cbcurrencyconverter_get_symbols
}

if ( ! function_exists( 'cbcurrencyconverter_get_symbol' ) ) {
	/**
	 * Get symbold for a currency using currency code
	 *
	 * @param $currency_code
	 *
	 * @return mixed|string
	 * @since 3.0.9
	 *
	 */
	function cbcurrencyconverter_get_symbol( $currency_code = '' ) {
		return CBCurrencyConverterHelper::getCurrencySymbol( $currency_code );
	}//end function cbcurrencyconverter_get_symbol
}


if(!function_exists('cbcurrencyconverter_rate')){
	/**
	 * Direct currency rate
	 *
	 * @param $from
	 * @param $to
	 * @param $amount
	 * @param $decimal_point
	 *
	 * @return string
	 * @since v3.1.0
	 */
	function cbcurrencyconverter_rate($from = '', $to = '', $amount = 1, $decimal_point = 2){
		return CBCurrencyConverterHelper::cbcurrencyconverter_rate($from, $to, $amount, $decimal_point);
	}//end function cbcurrencyconverter_rate
}