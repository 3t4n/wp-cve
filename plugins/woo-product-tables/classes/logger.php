<?php
define('WTBP_LOG', true);

class LoggerWtbp {

	public static function getInstance() {
		static $instance;
		if ( ! $instance ) {
			$instance = new LoggerWtbp();
		}

		return $instance;
	}

	public static function _() {
		return self::getInstance();
	}

	public function log( $message, $data = '' ) {
		if ( defined( 'WTBP_LOG' ) && WTBP_LOG === true ) {
			if ( ! is_string( $data ) && ! is_numeric( $data ) ) {
				$data = var_export( $data, true );
			}
			if ( ! function_exists( 'wc_get_logger' ) ) {
				include_once( ABSPATH . PLUGINDIR . '/woocommerce/woocommerce.php' );
			}
			wc_get_logger()->debug( "{$message} \n\n {$data} \n", array( '_legacy' => true ) );
		}
	}
}
