<?php

use Payever\Sdk\Core\Logger\FileLogger;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles log entries by writing to a file.
 */
class WC_Log_Handler_Filelogger extends WC_Log_Handler {

	private $logger;

	/**
	 * Constructor for the logger.
	 *
	 * @param $log_file_path
	 * @param $log_level
	 */
	public function __construct( $log_file_path, $log_level ) {
		$this->logger = new FileLogger( $log_file_path, $log_level );
	}
	/**
	 * Handle a log entry.
	 *
	 * @param int    $timestamp Log timestamp.
	 * @param string $level emergency|alert|critical|error|warning|notice|info|debug.
	 * @param string $message Log message.
	 * @param array  $context {
	 *      Additional information for log handlers.
	 *
	 *     @type string $source Optional. Determines log file to write to. Default 'log'.
	 *     @type bool $_legacy Optional. Default false. True to use outdated log format
	 *         originally used in deprecated WC_Logger::add calls.
	 * }
	 *
	 * @return bool False if value was not handled and true if value was handled.
	 */
	public function handle( $timestamp, $level, $message, $context ) {
		if ( isset( $context['source'] ) && 'payever' === $context['source'] ) {
			unset( $context['source'] );
		}
		$context['timestamp'] = $timestamp;

		$this->logger->log( $level, $message, $context );

		return true;
	}
}
