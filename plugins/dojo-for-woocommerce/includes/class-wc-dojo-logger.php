<?php

/**
 * Dojo Logger
 *
 * @package    Dojo_For_WooCommerce
 * @subpackage Dojo_For_WooCommerce/includes
 * @author     Dojo
 * @link       http://dojo.tech/
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}


if (!class_exists('WC_Dojo_Logger')) {
	/**
	 * Dojo logger
	 *
	 */
	class WC_Dojo_Logger
	{

		public static $logger;
		const WC_LOG_FILENAME = 'woocommerce-gateway-dojo';

		/**
		 * Telemetry API Client
		 *
		 * @var WC_Dojo_TelemetryApiClient
		 */
		private $telemetry_api_client;

		/**
		 * Dojo class constructor
		 */
		public function __construct()
		{
			$this->telemetry_api_client = new WC_Dojo_TelemetryApiClient();
		}

		/**
		 * Utilize WC logger class
		 *
		 * @since 4.0.0
		 * @version 4.0.0
		 */
		public function log($log_level, $method, $message, $api_key, $start_time = null, $end_time = null)
		{
			if (!class_exists('WC_Logger')) {
				return;
			}

			if (apply_filters('wc_dojo_logging', true, $message)) {
				if (empty(self::$logger)) {
					self::$logger = wc_get_logger();
				}

				if ($log_level === "Error" || $this->is_logging_setting_yes()) {

					$this->log_to_file($start_time, $end_time, $message, $log_level, ['source' => self::WC_LOG_FILENAME]);

					$this->telemetry_api_client->create_telemetry_log(WC_Dojo_Utils::create_telemetry_log_request(
						$log_level,
						$method,
						$message
					), $api_key);

				}
			}
		}

		private function is_logging_setting_yes() {
			$settings = get_option('woocommerce_dojo_settings');
			return (!empty($settings) || isset($settings['logging']) && $settings['logging'] == 'yes');
		}

		private function log_to_file($start_time, $end_time, $message, $log_level, $context) {
			if (!is_null($start_time)) {
				$formatted_start_time = date_i18n(get_option('date_format') . ' g:ia', $start_time);
				$end_time             = is_null($end_time) ? current_time('timestamp') : $end_time;
				$formatted_end_time   = date_i18n(get_option('date_format') . ' g:ia', $end_time);
				$elapsed_time         = round(abs($end_time - $start_time) / 60, 2);

				$log_entry  = "\n" . '====Dojo Version: ' . WC_DOJO_VERSION . '====' . "\n";
				$log_entry .= '====Start Log ' . $formatted_start_time . '====' . "\n" . $message . "\n";
				$log_entry .= '====End Log ' . $formatted_end_time . ' (' . $elapsed_time . ')====' . "\n\n";
			} else {
				$log_entry  = "\n" . '====Dojo Version: ' . WC_DOJO_VERSION . '====' . "\n";
				$log_entry .= '====Start Log====' . "\n" . $message . "\n" . '====End Log====' . "\n\n";
			}

			self::$logger->log(strtolower($log_level), $log_entry, $context);
		}
	}
}
