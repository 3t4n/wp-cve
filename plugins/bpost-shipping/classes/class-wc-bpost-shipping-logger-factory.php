<?php

use WC_BPost_Shipping\Options\WC_BPost_Shipping_Options_Base;

/**
 * Class WC_BPost_Shipping_Logger_Factory
 */
class WC_BPost_Shipping_Logger_Factory {

	/**
	 * @return WC_BPost_Shipping_Logger
	 */
	public function get_bpost_logger() {
		$handler = new WC_BPost_Shipping_Logger_Handler( new WC_Logger() );

		$options = new WC_BPost_Shipping_Options_Base();
		if ( ! $options->is_logs_debug_mode() ) {
			$handler->setLevel( \Monolog\Logger::ERROR );
		}

		return new WC_BPost_Shipping_Logger( BPOST_PLUGIN_ID, array( $handler ) );
	}
}

