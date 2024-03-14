<?php
/**
 * Handles logging for the plugin.
 *
 * @package        EverAccounting
 * @class          EverAccounting_Logger
 * @version        1.0.2
 */

namespace EverAccounting;

defined( 'ABSPATH' ) || exit;

/**
 * EverAccounting_Logger class.
 */
class Logger {

	/**
	 * Log level.
	 *
	 * @var string
	 */
	const EMERGENCY = 'emergency';
	/**
	 * Log level.
	 *
	 * @var string
	 */
	const ALERT = 'alert';
	/**
	 * Log level.
	 *
	 * @var string
	 */
	const CRITICAL = 'critical';
	/**
	 * Log level.
	 *
	 * @var string
	 */
	const ERROR = 'error';
	/**
	 * Log level.
	 *
	 * @var string
	 */
	const WARNING = 'warning';
	/**
	 * Log level.
	 *
	 * @var string
	 */
	const NOTICE = 'notice';
	/**
	 * Log level.
	 *
	 * @var string
	 */
	const INFO = 'info';
	/**
	 * Log level.
	 *
	 * @var string
	 */
	const DEBUG = 'debug';

	/**
	 * The file handler.
	 *
	 * @since 1.0.2
	 *
	 * @var null
	 */
	protected $handle = null;

	/**
	 * Log messages to be stored later.
	 *
	 * @since 1.0.2
	 *
	 * @var array
	 */
	protected $cached_logs = array();

	/**
	 * EverAccounting_Logger constructor.
	 *
	 * @since 1.0.2
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'write_cached_logs' ) );
	}

	/**
	 * Destructor.
	 *
	 * Cleans up open file handles.
	 *
	 * @since 1.0.2
	 */
	public function __destruct() {
		if ( is_resource( $this->handle ) ) {
			fclose( $this->handle ); // @codingStandardsIgnoreLine.
		}
	}

	/**
	 * Add a log entry.
	 *
	 * @param string $level One of the following:
	 *                        'emergency': System is unusable.
	 *                        'alert': Action must be taken immediately.
	 *                        'critical': Critical conditions.
	 *                        'error': Error conditions.
	 *                        'warning': Warning conditions.
	 *                        'notice': Normal but significant condition.
	 *                        'info': Informational messages.
	 *                        'debug': Debug-level messages.
	 * @param string $message Log message.
	 * @param array  $context Optional. Additional information for log handlers.
	 *
	 * @since 1.0.2
	 */
	public function log( $level, $message, $context = array() ) {
		// format log entry.
		$time         = date_i18n( 'm-d-Y @ H:i:s' );
		$level_string = strtoupper( $level );
		$entry        = "{$time} {$level_string} {$message}";

		$this->write_log( $entry );
	}

	/**
	 * Add a log entry to chosen file.
	 *
	 * @param string $entry Log entry text.
	 *
	 * @since 1.0.2
	 */
	protected function write_log( $entry ) {
		if ( $this->open( $this->handle ) && is_resource( $this->handle ) ) {
			fwrite( $this->handle, $entry . PHP_EOL ); // @codingStandardsIgnoreLine.
		} else {
			$this->cache_log( $entry );
		}
	}

	/**
	 * Cache log to write later.
	 *
	 * @param string $entry Log entry text.
	 *
	 * @since 1.0.2
	 */
	protected function cache_log( $entry ) {
		$this->cached_logs[] = $entry;
	}

	/**
	 * Open log file for writing.
	 *
	 * @param resource|null $handle Log handle.
	 * @param string        $name Optional. Name of the log file.
	 *
	 * @since 1.0.2
	 *
	 * @return bool Success.
	 */
	protected function open( $handle, $name = 'eaccounting' ) {
		if ( is_resource( $handle ) ) {
			return true;
		}

		$file = self::get_log_file_path( $name );

		if ( $file ) {
			@wp_mkdir_p( dirname( $file ) ); // @codingStandardsIgnoreLine.

			if ( ! file_exists( $file ) ) {
				$temphandle = @fopen( $file, 'wb+' ); // @codingStandardsIgnoreLine.
				@fclose( $temphandle ); // @codingStandardsIgnoreLine.

				if ( defined( 'FS_CHMOD_FILE' ) ) {
					@chmod( $file, FS_CHMOD_FILE ); // @codingStandardsIgnoreLine.
				}
			}

			$resource = @fopen( $file, 'ab' ); // @codingStandardsIgnoreLine.

			if ( $resource ) {
				$this->handle = $resource;

				return true;
			}
		}

		return false;
	}

	/**
	 * Close a handle.
	 *
	 * @param resource|string $handle Log handle.
	 *
	 * @since 1.0.2
	 *
	 * @return bool success
	 */
	protected function close( $handle ) {
		$result = false;

		if ( is_resource( $handle ) ) {
			$result = fclose( $this->handle ); // @codingStandardsIgnoreLine.
			unset( $this->handle );
		}

		return $result;
	}

	/**
	 * Get a log file path.
	 *
	 * @param string $name Optional. Name of the log file.
	 *
	 * @since 1.0.2
	 *
	 * @return bool|string The log file path or false if path cannot be determined.
	 */
	public static function get_log_file_path( $name ) {
		$date_suffix = date( 'Y-m-d', time() ); // @codingStandardsIgnoreLine.
		$name        = sanitize_file_name( implode( '-', array( $name, $date_suffix ) ) . '.log' );

		return trailingslashit( EACCOUNTING_LOG_DIR ) . $name;
	}

	/**
	 * Write cached logs.
	 *
	 * @since 1.0.2
	 */
	public function write_cached_logs() {
		foreach ( $this->cached_logs as $log ) {
			$this->write_log( $log );
		}
	}

	/**
	 * Clear all logs older than a defined number of days. Defaults to 30 days.
	 *
	 * @since 1.0.2
	 */
	public function clear_expired_logs() {
		$days      = absint( apply_filters( 'eaccounting_logger_days_to_retain_logs', 30 ) );
		$timestamp = strtotime( "-{$days} days" );

		$log_files = self::get_log_files();

		foreach ( $log_files as $log_file ) {
			$last_modified = filemtime( trailingslashit( EACCOUNTING_LOG_DIR ) . $log_file );

			if ( $last_modified < $timestamp ) {
				@unlink( trailingslashit( EACCOUNTING_LOG_DIR ) . $log_file ); // @codingStandardsIgnoreLine.
			}
		}
	}

	/**
	 * Get all log files in the log directory.
	 *
	 * @since 1.0.2
	 *
	 * @return array
	 */
	public static function get_log_files() {
		$files  = @scandir( EACCOUNTING_LOG_DIR ); // @codingStandardsIgnoreLine.
		$result = array();

		if ( ! empty( $files ) ) {
			foreach ( $files as $key => $value ) {
				if ( ! in_array( $value, array( '.', '..' ), true ) ) {
					if ( ! is_dir( $value ) && strpos( $value, '.log' ) !== false ) {
						$result[ sanitize_title( $value ) ] = $value;
					}
				}
			}
		}

		return $result;
	}

	/**
	 * Remove/delete the chosen file.
	 *
	 * @param string $file_name Name of the log file.
	 *
	 * @since 1.0.2
	 *
	 * @return bool
	 */
	public function remove( $file_name ) {
		$removed = false;
		$logs    = self::get_log_files();
		$handle  = sanitize_title( $file_name );

		if ( isset( $logs[ $handle ] ) && $logs[ $handle ] ) {
			$file = realpath( trailingslashit( EACCOUNTING_LOG_DIR ) . $logs[ $handle ] );
			if ( 0 === stripos( $file, realpath( trailingslashit( EACCOUNTING_LOG_DIR ) ) ) && is_file( $file ) && is_writable( $file ) ) {
				$this->close( $file ); // Close first to be certain no processes keep it alive after it is unlinked.
				$removed = unlink( $file );
			}
			do_action( 'eaccounting_log_remove', $handle, $removed );
		}

		return $removed;
	}


	/**
	 * Adds an emergency level message.
	 *
	 * System is unusable.
	 *
	 * @param string $message Message to log.
	 * @param array  $context Log context.
	 *
	 * @since 1.0.2
	 */
	public function log_emergency( $message, $context = array() ) {
		$this->log( self::EMERGENCY, $message, $context );
	}

	/**
	 * Adds an alert level message.
	 *
	 * Action must be taken immediately.
	 * Example: Entire website down, database unavailable, etc.
	 *
	 * @param string $message Message to log.
	 * @param array  $context Log context.
	 *
	 * @since 1.0.2
	 */
	public function log_alert( $message, $context = array() ) {
		$this->log( self::ALERT, $message, $context );
	}

	/**
	 * Adds a critical level message.
	 *
	 * Critical conditions.
	 * Example: Application component unavailable, unexpected exception.
	 *
	 * @param string $message Message to log.
	 * @param array  $context Log context.
	 *
	 * @since 1.0.2
	 */
	public function log_critical( $message, $context = array() ) {
		$this->log( self::CRITICAL, $message, $context );
	}

	/**
	 * Adds an error level message.
	 *
	 * Runtime errors that do not require immediate action but should typically be logged
	 * and monitored.
	 *
	 * @param string $message Message to log.
	 * @param array  $context Log context.
	 *
	 * @since 1.0.2
	 */
	public function log_error( $message, $context = array() ) {
		$this->log( self::ERROR, $message, $context );
	}

	/**
	 * Adds a warning level message.
	 *
	 * Exceptional occurrences that are not errors.
	 *
	 * Example: Use of deprecated APIs, poor use of an API, undesirable things that are not
	 * necessarily wrong.
	 *
	 * @param string $message Message to log.
	 * @param array  $context Log context.
	 *
	 * @since 1.0.2
	 */
	public function log_warning( $message, $context = array() ) {
		$this->log( self::WARNING, $message, $context );
	}

	/**
	 * Adds a notice level message.
	 *
	 * Normal but significant events.
	 *
	 * @param string $message Message to log.
	 * @param array  $context Log context.
	 *
	 * @since 1.0.2
	 */
	public function log_notice( $message, $context = array() ) {
		$this->log( self::NOTICE, $message, $context );
	}

	/**
	 * Adds a info level message.
	 *
	 * Interesting events.
	 * Example: User logs in, SQL logs.
	 *
	 * @param string $message Message to log.
	 * @param array  $context Log context.
	 *
	 * @since 1.0.2
	 */
	public function log_info( $message, $context = array() ) {
		$this->log( self::INFO, $message, $context );
	}

	/**
	 * Adds a debug level message.
	 *
	 * Detailed debug information.
	 *
	 * @param string $message Message to log.
	 * @param array  $context Log context.
	 *
	 * @since 1.0.2
	 */
	public function log_debug( $message, $context = array() ) {
		$this->log( self::DEBUG, $message, $context );
	}
}
