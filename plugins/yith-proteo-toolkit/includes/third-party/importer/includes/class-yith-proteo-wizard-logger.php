<?php
/**
 * The logger class
 *
 * @package YITH_Proteo_tookit
 */

/**
 * The logger class
 */
class YITH_Proteo_Wizard_Logger {


	/**
	 * The instance *Singleton* of this class
	 *
	 * @var object
	 */
	private static $instance;


	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @return object *Singleton* instance.
	 *
	 * @codeCoverageIgnore Nothing to test, default PHP singleton functionality.
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}


	/**
	 * Log message for log level: debug.
	 *
	 * @param string $message The log message.
	 * @param array  $context The log context.
	 *
	 * @return boolean Whether the record has been processed.
	 */
	public function debug( $message, $context = array() ) {
		return error_log( $message ); // phpcs:ignore
	}

	/**
	 * Log message for log level: info.
	 *
	 * @param string $message The log message.
	 * @param array  $context The log context.
	 *
	 * @return boolean Whether the record has been processed.
	 */
	public function info( $message, $context = array() ) {
		return error_log( $message ); // phpcs:ignore
	}


	/**
	 * Log message for log level: notice.
	 *
	 * @param string $message The log message.
	 * @param array  $context The log context.
	 *
	 * @return boolean Whether the record has been processed.
	 */
	public function notice( $message, $context = array() ) {
		return error_log( $message ); // phpcs:ignore
	}


	/**
	 * Log message for log level: warning.
	 *
	 * @param string $message The log message.
	 * @param array  $context The log context.
	 *
	 * @return boolean Whether the record has been processed.
	 */
	public function warning( $message, $context = array() ) {
		return error_log( $message ); // phpcs:ignore
	}


	/**
	 * Log message for log level: error.
	 *
	 * @param string $message The log message.
	 * @param array  $context The log context.
	 *
	 * @return boolean Whether the record has been processed.
	 */
	public function error( $message, $context = array() ) {
		return error_log( $message ); // phpcs:ignore
	}


	/**
	 * Log message for log level: alert.
	 *
	 * @param string $message The log message.
	 * @param array  $context The log context.
	 *
	 * @return boolean Whether the record has been processed.
	 */
	public function alert( $message, $context = array() ) {
		return error_log( $message ); // phpcs:ignore
	}


	/**
	 * Log message for log level: emergency.
	 *
	 * @param string $message The log message.
	 * @param array  $context The log context.
	 *
	 * @return boolean Whether the record has been processed.
	 */
	public function emergency( $message, $context = array() ) {
		return error_log( $message ); // phpcs:ignore
	}


	/**
	 * Avoid clone method to be overrided.
	 *
	 * @return void
	 */
	final public function __clone() {}


	/**
	 * Avoid override of unserialize method.
	 *
	 * @return void
	 */
	final public function __wakeup() {}
}
