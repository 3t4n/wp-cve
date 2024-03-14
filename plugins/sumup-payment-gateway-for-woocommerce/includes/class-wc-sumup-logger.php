<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Utilize WC logger class
 *
 * @since   1.0.0
 * @version 1.0.0
 */
class WC_SUMUP_LOGGER {
	/**
	 * Add a log entry.
	 *
	 * @param string $message Log message.
	 */
	public static function log( $message ) {
		if ( ! class_exists( 'WC_Logger' ) ) {
			return;
		}

		$options     = get_option( 'woocommerce_sumup_settings' );
		$client_id   = $options['client_id'];

		if ( empty( $options ) || ( isset( $options['logging'] ) && 'yes' !== $options['logging'] ) ) {
			return;
		}

		$logger = wc_get_logger();
		$context = array( 'source' => WC_SUMUP_PLUGIN_SLUG );

		$log_message  = PHP_EOL . '==== SumUp Version: ' . WC_SUMUP_VERSION . ' ====' . PHP_EOL;
		$log_message .= 'Client ID: ' . $client_id . PHP_EOL;
		$log_message .= PHP_EOL;
		$log_message .= '=== Start Log ===' . PHP_EOL;
		$log_message .= $message . PHP_EOL;
		$log_message .= '=== End Log ===' . PHP_EOL;
		$log_message .= PHP_EOL;

		$logger->debug( $log_message, $context );
	}
}
