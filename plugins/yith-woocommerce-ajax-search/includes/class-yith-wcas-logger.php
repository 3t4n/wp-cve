<?php
/**
 * Handle log action using WC_Logger.
 *
 * @package YITH/Search
 * @since 2.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * The class
 */
class YITH_WCAS_Logger {
	use YITH_WCAS_Trait_Singleton;

	/**
	 * WC logger instance
	 *
	 * @var WC_Logger
	 */
	public static $logger_object = null;


	/**
	 * Constructor
	 */
	private function __construct() {
		add_action( 'init', array( __CLASS__, 'init' ), 30 );
	}


	/**
	 * Init the WC Logger
	 *
	 * @return void
	 * @since 2.0.0
	 */
	public static function init() {
		if ( is_null( self::$logger_object ) && class_exists( 'WC_Logger' ) ) {
			self::$logger_object = new WC_Logger();
		}
	}

	/**
	 * Log a message
	 *
	 * @param string $message The message to log.
	 * @param string $type The message type.
	 *
	 * @return void
	 * @since 2.0.0
	 */
	public function log( $message, $type = 'search' ) {
		self::init();

		if ( ! is_null( self::$logger_object ) ) {
			self::$logger_object->add(
				'yith-wcas-' . $type,
				$message
			);
		}
	}


}
