<?php
if (!function_exists('wcfm_paypal_log')) {
	/**
	 * Add a log entry.
	 * 
	 * @since 2.0.0
	 * 
	 * @return WC_Logger
	 *
	 * @param string $message Log message.
	 * @param string $level One of the following:
	 *     'emergency': System is unusable.
	 *     'alert': Action must be taken immediately.
	 *     'critical': Critical conditions.
	 *     'error': Error conditions.
	 *     'warning': Warning conditions.
	 *     'notice': Normal but significant condition.
	 *     'info': Informational messages.
	 *     'debug': Debug-level messages.
	 */
	function wcfm_paypal_log($message, $level = 'debug') {
		wcfm_create_log($message, $level, 'wcfm-paypal');
	}
}
/**
 * 
 * Get PayPal gateway id
 *
 * @since 2.0.0
 *
 * @return string
 */
function get_gateway_id() {
	// do not change this value ever, otherwise this will cause inconsistancy while retrieving data
	return 'wcfm_paypal_marketplace';
}

/**
 *
 * @see https://developer.paypal.com/docs/platforms/develop/currency-codes/
 *
 * @since 2.0.0
 *
 * @return array
 */
function get_supported_currencies() {
	$supported_currencies = [
		'AUD' => __( 'Australian dollar', 'wc-frontend-manager-direct-paypal' ),
		'BRL' => __( 'Brazilian real', 'wc-frontend-manager-direct-paypal' ),
		'CAD' => __( 'Canadian dollar', 'wc-frontend-manager-direct-paypal' ),
		'CNY' => __( 'Chinese Renmenbi', 'wc-frontend-manager-direct-paypal' ),
		'CZK' => __( 'Czech koruna', 'wc-frontend-manager-direct-paypal' ),
		'DKK' => __( 'Danish krone', 'wc-frontend-manager-direct-paypal' ),
		'EUR' => __( 'Euro', 'wc-frontend-manager-direct-paypal' ),
		'HKD' => __( 'Hong Kong dollar', 'wc-frontend-manager-direct-paypal' ),
		'HUF' => __( 'Hungarian forint', 'wc-frontend-manager-direct-paypal' ),
		'ILS' => __( 'Israeli new shekel', 'wc-frontend-manager-direct-paypal' ),
		'JPY' => __( 'Japanese yen', 'wc-frontend-manager-direct-paypal' ),
		'MYR' => __( 'Malaysian ringgit', 'wc-frontend-manager-direct-paypal' ),
		'MXN' => __( 'Mexican peso', 'wc-frontend-manager-direct-paypal' ),
		'TWD' => __( 'New Taiwan dollar', 'wc-frontend-manager-direct-paypal' ),
		'NZD' => __( 'New Zealand dollar', 'wc-frontend-manager-direct-paypal' ),
		'NOK' => __( 'Norwegian krone', 'wc-frontend-manager-direct-paypal' ),
		'PHP' => __( 'Philippine peso', 'wc-frontend-manager-direct-paypal' ),
		'PLN' => __( 'Polish złoty', 'wc-frontend-manager-direct-paypal' ),
		'GBP' => __( 'Pound sterling', 'wc-frontend-manager-direct-paypal' ),
		'RUB' => __( 'Russian ruble', 'wc-frontend-manager-direct-paypal' ),
		'SGD' => __( 'Singapore dollar', 'wc-frontend-manager-direct-paypal' ),
		'SEK' => __( 'Swedish krona', 'wc-frontend-manager-direct-paypal' ),
		'CHF' => __( 'Swiss franc', 'wc-frontend-manager-direct-paypal' ),
		'THB' => __( 'Thai baht', 'wc-frontend-manager-direct-paypal' ),
		'USD' => __( 'United States dollar', 'wc-frontend-manager-direct-paypal' ),
	];

	return apply_filters( 'wcfm_paypal_supported_currencies', $supported_currencies );
}

/**
 * Get advanced credit card debit card supported countries
 * 
 * @see https://developer.paypal.com/docs/checkout/advanced/#link-eligibility
 */
function get_advanced_credit_and_debit_supported_countries() {
	return apply_filters('wcfm_paypal_advanced_credit_and_debit_supported_countries', [
		'AU' => ['AUD', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HUF', 'JPY', 'NOK', 'NZD', 'PLN', 'SEK', 'SGD', 'USD'],
		'CA' => ['AUD', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HUF', 'JPY', 'NOK', 'NZD', 'PLN', 'SEK', 'SGD', 'USD'],
		'FR' => ['AUD', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HUF', 'JPY', 'NOK', 'NZD', 'PLN', 'SEK', 'SGD', 'USD'],
		'DE' => ['AUD', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HUF', 'JPY', 'NOK', 'NZD', 'PLN', 'SEK', 'SGD', 'USD'],
		'IT' => ['AUD', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HUF', 'JPY', 'NOK', 'NZD', 'PLN', 'SEK', 'SGD', 'USD'],
		'ES' => ['AUD', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HUF', 'JPY', 'NOK', 'NZD', 'PLN', 'SEK', 'SGD', 'USD'],
		'US' => ['AUD', 'CAD', 'EUR', 'GBP', 'JPY', 'USD'],
		'GB' => ['AUD', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HUF', 'JPY', 'NOK', 'NZD', 'PLN', 'SEK', 'SGD', 'USD'],
		'MX' => ['MXN'],
		'JP' => ['AUD', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HUF', 'JPY', 'NOK', 'NZD', 'PLN', 'SEK', 'SGD', 'USD'],
	]);
}

/**
 * @since 2.0.0
 * 
 * @return int
 */
function get_wcfm_current_vendor_id() {
	return apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
}

/**
 * @since 2.0.0
 * 
 * @return string
 */
function wcfm_get_endpoint_url_payment_tab() {
	return untrailingslashit(wcfm_get_endpoint_url('wcfm-settings', '', get_wcfm_page())) . '#wcfm_settings_form_payment_head';
}

/**
 * @since 2.0.0
 * 
 * @return array
 */
if (!function_exists('getallheaders')) {
    function getallheaders() {
    	$headers = [];
       	foreach ($_SERVER as $name => $value) {
           	if (substr($name, 0, 5) == 'HTTP_') {
               	$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
           	}
       	}
       	return $headers;
    }
}