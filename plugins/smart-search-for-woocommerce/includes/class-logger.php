<?php
/**
 * Searchanise logger
 *
 * @package Searchanise/Logger
 */

namespace Searchanise\SmartWoocommerceSearch;

defined( 'ABSPATH' ) || exit;

/**
 * Searchanise logger class
 */
class Logger {

	const DEBUG_VAR_NAME     = 'debug_module_searchanise';
	const DEBUG_LOG_VAR_NAME = 'log_module_searchanise';
	const DEBUG_KEY          = 'Y';

	const TYPE_DEBUG = 'debug';
	const TYPE_ERROR = 'error';

	/**
	 * Log files list
	 *
	 * @var array
	 */
	private $log_files = array(
		self::TYPE_ERROR => 'error.log',
		self::TYPE_DEBUG => 'debug.log',
	);

	/**
	 * Logging directory
	 *
	 * @var string
	 */
	private $log_dir = '';

	/**
	 * If true, log errors
	 *
	 * @var bool
	 */
	private $log_errors = false;

	/**
	 * If true, log debug data
	 *
	 * @var bool
	 */
	private $log_debug = false;

	/**
	 * If true, print to output
	 *
	 * @var bool
	 */
	private $output_debug = false;

	/**
	 * Self instance
	 *
	 * @var Logger
	 */
	private static $self = null;

	/**
	 * Logger constructor
	 *
	 * @param array $options Logger options.
	 */
	public function __construct( $options = array() ) {
		foreach ( $options as $option => $value ) {
			if ( property_exists( $this, $option ) ) {
				$this->{$option} = $value;
			}
		}

		if ( ! empty( $this->log_dir ) && ! file_exists( $this->log_dir ) ) {
			mkdir( $this->log_dir, 0777, true );
		}
	}

	/**
	 * Returns logger instance
	 *
	 * @param array $options Logger options.
	 */
	public static function get_instance( $options = array() ) {
		if ( null === self::$self ) {
			self::$self = new self( $options );
		}

		return self::$self;
	}

	/**
	 * Log error data
	 *
	 * @param mixed $data Data to log.
	 */
	public function error( $data ) {
		$this->log( $data, self::TYPE_ERROR );
		$this->output( $data );
	}

	/**
	 * Log debug data
	 *
	 * @param mixed $data Data to log.
	 */
	public function debug( $data ) {
		$this->log( $data, self::TYPE_DEBUG );
		$this->output( $data );
	}

	/**
	 * Clear log files
	 */
	public function clear_logs() {
		if ( ! empty( $this->log_dir ) && file_exists( $this->log_dir ) ) {
			foreach ( $this->log_files as $file ) {
				$file = rtrim( $this->log_dir, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . $file;

				if ( file_exists( $file ) ) {
					@unlink( $file );
				}
			}
		}
	}

	/**
	 * Log data
	 *
	 * @param mixed  $data Data to log.
	 * @param string $type Log type.
	 */
	private function log( $data, $type ) {
		if (
			! $this->is_log_errors_enabled() && self::TYPE_ERROR == $type
			|| ! $this->is_log_debug_enabled() && self::TYPE_DEBUG == $type
		) {
			return;
		}

		$date = gmdate( 'c' );
		$message = "Searchanise: # {$type}: " . print_r( $data, true );
		$file = $this->log_files[ $type ];

		if ( ! empty( $this->log_dir ) && file_exists( $this->log_dir ) ) {
			$full_path = rtrim( $this->log_dir, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . $file;

			$f = fopen( $full_path, 'a+' );
			if ( false === $f ) {
				return;
			}

			@fwrite( $f, "\n" . $date . "\n" );
			@fwrite( $f, $message );
			@fwrite( $f, "\n" );
			@fclose( $f );
		}
	}

	/**
	 * Output debug data
	 *
	 * @param mixed $data Data to log.
	 */
	private function output( $data ) {
		if (
			! $this->is_debug_enabled()
			|| ( defined( 'DOING_CRON' ) && DOING_CRON )
		) {
			return;
		}

		if ( is_array( $data ) ) {
			foreach ( $data as $k => &$v ) {
				if ( ! is_array( $v ) && preg_match( '~[^\x20-\x7E\t\r\n]~', $v ) > 0 ) {
					$v = '=== BINARY DATA ===';
				}
			}
		}

		$this->print_r( $data );
	}

	/**
	 * Checks if log errors is enabled
	 *
	 * @return boolean
	 */
	public function is_log_errors_enabled() {
		return $this->log_errors || ( isset( $_REQUEST[ self::DEBUG_LOG_VAR_NAME ] ) && self::DEBUG_KEY == $_REQUEST[ self::DEBUG_LOG_VAR_NAME ] );
	}

	/**
	 * Checks if log debug is enabled
	 *
	 * @return boolean
	 */
	public function is_log_debug_enabled() {
		return $this->log_debug || ( isset( $_REQUEST[ self::DEBUG_LOG_VAR_NAME ] ) && self::DEBUG_KEY == $_REQUEST[ self::DEBUG_LOG_VAR_NAME ] );
	}

	/**
	 * Checks if debug output is enabled
	 *
	 * @return boolean
	 */
	private function is_debug_enabled() {
		return $this->output_debug || ( isset( $_REQUEST[ self::DEBUG_VAR_NAME ] ) && self::DEBUG_KEY == $_REQUEST[ self::DEBUG_VAR_NAME ] );
	}

	/**
	 * Log output
	 */
	public function print_r() {
		call_user_func_array( array( Api::get_instance(), 'print_r' ), func_get_args() );
	}
}
