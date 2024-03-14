<?php

/**
 * Dojo Utils
 *
 * @package    Dojo_For_WooCommerce
 * @subpackage Dojo_For_WooCommerce/includes
 * @author     Dojo
 * @link       http://dojo.tech/
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('WC_Dojo_Utils')) {

	require_once __DIR__ . '/class-wc-dojo-logger.php';

	/**
	 * Dojo Utils
	 *
	 */
	class WC_Dojo_Utils
	{

		/**
		 * Gets an element from array
		 *
		 * @param array  $arr     The array.
		 * @param string $element The element.
		 * @param mixed  $default The default value if the element does not exist.
		 *
		 * @return mixed
		 */
		public static function get_array_element($arr, $element, $default)
		{
			return (is_array($arr) && array_key_exists($element, $arr))
				? (is_string($arr[$element]) ? sanitize_text_field($arr[$element]) : $arr[$element])
				: $default;
		}

		/**
		 * Gets order by payment intent ID.
		 *
		 * @param string $payment_intent_id Payment intent ID.
		 *
		 * @return order Order is returned.
		 */
		public static function get_order_by_payment_intent_id($payment_intent_id)
		{
			$orders = wc_get_orders(
				array(
					'meta_key'      => 'payment_intent_id',
					'meta_value'    => $payment_intent_id,
					'meta_compare'  => '='
				)
			);
			return $orders[0];
		}


		/**
		 * Converts an array to string
		 *
		 * @param array  $arr    An associative array.
		 * @param string $indent Indentation.
		 *
		 * @return string
		 */
		public static function convert_array_to_string($arr, $indent = '')
		{
			$result         = '';
			$indent_pattern = '  ';
			foreach ($arr as $key => $value) {
				if ('' !== $result) {
					$result .= PHP_EOL;
				}
				if (is_array($value)) {
					$value = PHP_EOL . self::convert_array_to_string($value, $indent . $indent_pattern);
				}
				$result .= $indent . $key . ': ' . $value;
			}
			return $result;
		}

		/**
		 * Checks if a string is null or empty
		 *
		 * @param string $str string to check.
		 *
		 * @return bool
		 */
		public static function is_null_or_empty($str)
		{
			return ($str === null || trim($str) === '');
		}

		public static function create_telemetry_log_request($log_level, $message, $method)
		{
			$current_time = new Datetime();
			return [
				'SourceType'		=> 'DojoPlugin',
				'DojoPluginInfo'	=> [
					'PlatformName'   	=> 'WooCommerce',
					'PlatformVersion'	=> (string) WC()->version,
					'PluginVersion'   	=> (string) WC_DOJO_VERSION,
				],
				'LogLevel'			=> (string)	$log_level,
				'Method'       		=> (string) $message,
				'Message'			=> (string) $method,
				'CreatedAt'			=> $current_time->format(DateTime::RFC3339_EXTENDED)
			];
		}
	}
}
