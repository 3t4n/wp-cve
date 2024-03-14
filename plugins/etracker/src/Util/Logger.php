<?php
/**
 * The logger of the plugin.
 *
 * @link       https://etracker.com
 * @since      2.0.0
 *
 * @package    Etracker
 */

namespace Etracker\Util;

use Etracker\Database\LoggingTable;
use Etracker\Util\Exceptions\LogLevelInvalidException;

/**
 * The logger functionality of the plugin.
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class Logger {
	/**
	 * Database table to store logging messages in.
	 *
	 * @var LoggingTable
	 */
	private $table;

	/**
	 * Logging level.
	 *
	 * @var integer
	 */
	private $level;

	/**
	 * Log message prefix.
	 *
	 * @var string $prefix Log message prefix.
	 */
	private $prefix;

	/**
	 * Emergency: system is unusable.
	 */
	public const EMERGENCY = 0;

	/**
	 * Alert: action must be taken immediately.
	 */
	public const ALERT = 1;

	/**
	 * Critical: critical conditions.
	 */
	public const CRITICAL = 2;

	/**
	 * Error: error conditions.
	 */
	public const ERROR = 3;

	/**
	 * Warning: warning conditions.
	 */
	public const WARNING = 4;

	/**
	 * Notice: normal but significant condition.
	 */
	public const NOTICE = 5;

	/**
	 * Informational: informational messages.
	 */
	public const INFO = 6;

	/**
	 * Debug: debug-level messages.
	 */
	public const DEBUG = 7;

	/**
	 * Contructor.
	 */
	public function __construct() {
		$this->table  = new LoggingTable();
		$this->level  = self::NOTICE;
		$this->prefix = '';

		/**
		 * Filters the plugin log level.
		 *
		 * Adding a filter to `etracker_log_level` allows you to change
		 * the log level for this plugin. Log messages are stored within
		 * the database and can be seen in settings section of this plugin.
		 *
		 * Example usage:
		 *
		 * ```
		 * function my_theme_etracker_set_log_level( $current_log_level ) {
		 *  // Log only emergency (highest) level messages.
		 *  // return 0;
		 *  // Log alert or higher level messages.
		 *  // return 1;
		 *  // Log critical or higher level messages.
		 *  // return 2;
		 *  // Log error or higher level messages.
		 *  // return 3;
		 *  // Log warning or higher level messages.
		 *  // return 4;
		 *  // Log notice or higher level messages.
		 *  // return 5;
		 *  // Log info or higher level messages.
		 *  return 6;
		 *  // Log debug or higher level messages. (This means, log all messages.)
		 *  // return 7;
		 * };
		 *
		 * add_filter( 'etracker_log_level', 'my_theme_etracker_set_log_level' );
		 * ```
		 *
		 * @since 2.0.0
		 *
		 * @param integer $maxage Max age in days.
		 */
		$log_level = \apply_filters( 'etracker_log_level', $this->level );

		$this->set_level( (int) $log_level );
	}

	/**
	 * Store logging message with given priority.
	 *
	 * Syslog compatibe priorities:
	 *
	 *   0  Emergency
	 *   1  Alert
	 *   2  Critical
	 *   3  Error
	 *   4  Warning
	 *   5  Notice
	 *   6  Informational
	 *   7  Debug
	 *
	 * @param integer $priority Syslog compatible priority.
	 * @param string  $message  Message to be logged.
	 *
	 * @return int|bool Number of rows affected. Boolean false on error.
	 */
	private function store( int $priority, string $message ) {
		// Apply all defined filters for etracker_log_level to modify log level.
		$log_level = \apply_filters( 'etracker_log_level', $this->level );

		$this->set_level( (int) $log_level );

		if ( $this->level >= $priority ) {
			return $this->table->store_message( $priority, $this->prefix . $message );
		}
		return 0;
	}

	/**
	 * Sets loglevel for logger.
	 *
	 * @param integer $level Loglevel should be between 0 and 7.
	 *
	 * @throws LogLevelInvalidException Thrown if new $level is out of range.
	 *
	 * @return void
	 */
	public function set_level( int $level ) {
		if ( 0 > $level || 7 < $level ) {
			// Level out of range.
			throw new LogLevelInvalidException( "Level $level is invalid." );
		}
		$this->level = $level;
	}

	/**
	 * Returns loglevel of logger.
	 *
	 * @return int Log Level.
	 */
	public function get_level() {
		return $this->level;
	}

	/**
	 * Returns current log level as string.
	 *
	 * @return string
	 */
	public function get_level_name(): string {
		switch ( $this->level ) {
			case 0:
				return 'Emergency';
			case 1:
				return 'Alert';
			case 2:
				return 'Critical';
			case 3:
				return 'Error';
			case 4:
				return 'Warning';
			case 5:
				return 'Notice';
			case 6:
				return 'Informational';
			case 7:
				return 'Debug';
			default:
				return 'invalid';
		}
	}

	/**
	 * Sets log message prefix.
	 *
	 * @param string $prefix Message prefix.
	 *
	 * @return void
	 */
	public function set_prefix( string $prefix ) {
		if ( ' ' !== substr( $prefix, -1 ) ) {
			// Add ' ' as glow char between prefix and message.
			$prefix .= ' ';
		}
		$this->prefix = $prefix;
	}

	/**
	 * Log debug message $message.
	 *
	 * @param string $message Message to be logged.
	 *
	 * @return int|bool Number of rows affected. Boolean false on error.
	 */
	public function debug( string $message ) {
		return $this->store( self::DEBUG, $message );
	}

	/**
	 * Log informational message $message.
	 *
	 * @param string $message Message to be logged.
	 *
	 * @return int|bool Number of rows affected. Boolean false on error.
	 */
	public function info( string $message ) {
		return $this->store( self::INFO, $message );
	}

	/**
	 * Log notice message $message.
	 *
	 * @param string $message Message to be logged.
	 *
	 * @return int|bool Number of rows affected. Boolean false on error.
	 */
	public function notice( string $message ) {
		return $this->store( self::NOTICE, $message );
	}

	/**
	 * Log warning message $message.
	 *
	 * @param string $message Message to be logged.
	 *
	 * @return int|bool Number of rows affected. Boolean false on error.
	 */
	public function warning( string $message ) {
		return $this->store( self::WARNING, $message );
	}

	/**
	 * Log error message $message.
	 *
	 * @param string $message Message to be logged.
	 *
	 * @return int|bool Number of rows affected. Boolean false on error.
	 */
	public function error( string $message ) {
		return $this->store( self::ERROR, $message );
	}

	/**
	 * Log critical message $message.
	 *
	 * @param string $message Message to be logged.
	 *
	 * @return int|bool Number of rows affected. Boolean false on error.
	 */
	public function critical( string $message ) {
		return $this->store( self::CRITICAL, $message );
	}

	/**
	 * Log alert message $message.
	 *
	 * @param string $message Message to be logged.
	 *
	 * @return int|bool Number of rows affected. Boolean false on error.
	 */
	public function alert( string $message ) {
		return $this->store( self::ALERT, $message );
	}

	/**
	 * Log emergency message $message.
	 *
	 * @param string $message Message to be logged.
	 *
	 * @return int|bool Number of rows affected. Boolean false on error.
	 */
	public function emergency( string $message ) {
		return $this->store( self::EMERGENCY, $message );
	}
}
