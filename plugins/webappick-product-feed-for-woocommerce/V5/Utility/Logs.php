<?php

namespace CTXFeed\V5\Utility;

use CTXFeed\V5\Common\Helper;
use Exception;
use WC_Logger;
use CTXFeed\V5\Utility\CTX_WC_Log_Handler;

/**
 * Log Helper Functions
 *
 * @package    CTXFeed
 * @subpackage CTX_WC_Log_Handler
 * @since      WooFeed 3.3.0
 * @version    1.0.0
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @copyright  WebAppick
 */
class Logs {

	private static $is_debug_enabled;

	public function __construct() {
		self::$is_debug_enabled = Helper::is_debugging_enabled();
	}

	public static function get_logger() {
		if ( ! class_exists( CTX_WC_Log_Handler::class ) ) {
			return false;
		}

		static $logger = null;
		if ( $logger instanceof \WC_Logger ) {
			return $logger;
		}

		if ( ! class_exists( 'WC_Logger' ) ) {
			return $logger;
		}


		return new WC_Logger( [ new CTX_WC_Log_Handler() ] );
	}


	/**
	 * Write message to log file.
	 * Write log message if debugging is enabled
	 *
	 * @param string $source will be use for log file name.
	 * @param string $message Log message.
	 * @param string $level One of the following:
	 *                          'emergency': System is unusable.
	 *                          'alert': Action must be taken immediately.
	 *                          'critical': Critical conditions.
	 *                          'error': Error conditions.
	 *                          'warning': Warning conditions.
	 *                          'notice': Normal but significant condition.
	 *                          'info': Informational messages.
	 *                          'debug': Debug-level messages.
	 * @param mixed $data Extra data for the log handler.
	 * @param bool $force_log ignore debugging settings
	 * @param bool $wc_log log data in wc-logs directory
	 *
	 * @return void
	 * @since 3.2.1
	 *
	 */
	public static function write_log( $source, $message, $level = 'debug', $data = null, $force_log = true, $wc_log = false ) {
		if ( true === $force_log || Helper::is_debugging_enabled() ) {

			if ( ! in_array( $level, [
				'emergency',
				'alert',
				'critical',
				'critical',
				'error',
				'warning',
				'notice',
				'info',
				'debug',
			], true ) ) {
				return;
			}
			$context = [ 'source' => $source ];
			if ( is_array( $data ) ) {
				if ( isset( $data['source'] ) ) {
					unset( $data['source'] );
				}
				$context = array_merge( $context, $data );
			} else {
				$context['data'] = $data;
			}

			$loggers = [ self::get_logger() ];
			if ( true === $wc_log && function_exists( 'wc_get_logger' ) ) {
				$loggers[] = wc_get_logger();
			}

			foreach ( $loggers as $logger ) {
				if ( is_callable( [ $logger, $level ] ) ) {
					$logger->$level( $message . PHP_EOL, $context );
				}
			}
		}
	}

	/**
	 * Log Fatal Errors in both wc-logs and woo-feed/logs
	 *
	 * @param string $message The log message.
	 * @param mixed $data Extra data for the log handler.
	 */
	public static function write_fatal_log( $message, $data = null ) {
		// woocommerce use 'fatal-errors' as log handler...
		// make no conflicts with woocommerce fatal-errors logs
		self::write_log( 'ctx-feed-fatal-errors', $message, 'critical', $data, true, true );
	}

	/**
	 * Log Fatal Errors in both wc-logs and woo-feed/logs
	 *
	 * @param string $message The log message.
	 * @param mixed $data Extra data for the log handler.
	 */
	public static function write_debug_log( $message, $data = null ) {
		// woocommerce use 'fatal-errors' as log handler...
		// make no conflicts with woocommerce fatal-errors logs
		self::write_log( 'ctx-feed-fatal-errors', $message, 'debug', $data, true, true );
	}

	/**
	 * Delete Log file by source or handle name
	 *
	 * @param string $source log source or handle name
	 * @param bool $handle use source as handle
	 *
	 * @return bool
	 */
	public static function delete_log( $source, $handle = false ) {
		$log_handler = new CTX_WC_Log_Handler();

		try {
			if ( 'ctx-feed-fatal-errors' === $source ) {
				// fatal error are also logged in wc-logs dir.
				if ( class_exists( 'WC_Log_Handler_File', false ) ) {
					$source      = false === $handle ? $log_handler::get_log_file_name( $source ) : $source;
					$log_handler = new \WC_Log_Handler_File();

					return $log_handler->remove( $source );
				}
			} else {
				$source = ! $handle ? $log_handler::get_log_file_name( $source ) : $source;

				return $log_handler->remove( $source );
			}
		} catch ( Exception $e ) {
			return false;
		}

		return false;
	}

	/**
	 * Delete all log files.
	 *
	 * @return void
	 */
	public static function delete_all_logs() {
		// delete the fatal error log
		self::delete_log( 'ctx-feed-fatal-errors' );
		// get all logs
		$logs = CTX_WC_Log_Handler::get_log_files();
		foreach ( $logs as $log ) {
			self::delete_log( $log, true );
		}
	}


	/**
	 * Trigger logging cleanup using the logging class.
	 *
	 * @return void
	 */
	public static function cleanup_logs() {
		$logger = self::get_logger();
		if ( is_callable( array( $logger, 'clear_expired_logs' ) ) ) {
			$logger->clear_expired_logs();
		}
	}


}
// End of file logs class.
