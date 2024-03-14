<?php
/**
 * Logger trait
 *
 * Provides shared logic for logging errors
 *
 * @package WooCommerce
 * @category Payment Gateways
 * @author Revolut
 * @since 2.0
 */

defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'WC_Logger' ) ) {
	return;
}

/**
 * WC_Revolut_Logger_Trait trait.
 */
trait WC_Revolut_Logger_Trait {


	/**
	 * Logger status
	 *
	 * @var boolean
	 */
	protected $enable_logging = true;

	/**
	 * Return error message
	 *
	 * @param String $message Log message.
	 */
	public function log_error( $message ) {
		$logger  = wc_get_logger();
		$context = array( 'source' => 'revolut-gateway-for-woocommerce' );

		if ( is_array( $message ) ) {
			$message = wp_json_encode( $message );
		}

		$logger->error( $message, $context );
	}

	/**
	 * Return error message
	 *
	 * @param String $message Log message.
	 */
	public function log_info( $message ) {
		$logger  = wc_get_logger();
		$context = array( 'source' => 'revolut-gateway-for-woocommerce' );

		if ( is_array( $message ) ) {
			$message = wp_json_encode( $message );
		}

		$logger->info( $message, $context );
	}
}
