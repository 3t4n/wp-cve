<?php
/**
 * Base class for the audit process.
 *
 * @version     2016-12-20 11:41 UTC+01
 * @package     Watchful WP Client
 * @author      Watchful
 * @authorUrl   https://watchful.net
 * @copyright   Copyright (c) 2020 watchful.net
 * @license     GNU/GPL
 */

namespace Watchful\Audit;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Watchful Audit Process class.
 */
abstract class AuditProcess {

	/**
	 * Default time execution limit.
	 *
	 * @var int
	 */
	const _DEFAULT_TIMEEXECUTION_LIMIT = 10;

	/**
	 * Maximum execution time.
	 *
	 * @var int
	 */
	private $max_execution_time;

	/**
	 * Start time.
	 *
	 * @var int
	 */
	private $start_time;

	/**
	 * Cache.
	 *
	 * @var string
	 */
	public $cache;

	/**
	 * The class constructor.
	 */
	public function __construct() {
		$this->start_time         = $this->microtime_float();
		$this->max_execution_time = $this->calculate_max_execution_time();
	}

	/**
	 * Get the maximum execution time from the system
	 *
	 * @return int
	 */
	private function calculate_max_execution_time() {
		$max_executiontime_set_by_master = get_query_var( 'max_execution_time', 0 );

		if ( $max_executiontime_set_by_master ) {
			return $max_executiontime_set_by_master;
		}

		$php_execution_time = (int) ini_get( 'max_execution_time' );

		if ( ! $php_execution_time ) {
			return self::_DEFAULT_TIMEEXECUTION_LIMIT;
		}

		// Return 2 seconds less than the `max_execution_time` in the server PHP config.
		return $php_execution_time - 2;
	}

	/**
	 * Get the maximum execution time.
	 *
	 * @return int
	 */
	public function get_max_execution_time() {
		return $this->max_execution_time;
	}

	/**
	 * Calculate if we have 1 second left
	 *
	 * @return boolean
	 */
	public function have_time() {
		$available_time = $this->max_execution_time - $this->have_run();

		return $available_time > 1;
	}

	/**
	 * Nomber of second from the start of the script
	 *
	 * @return decimal
	 */
	private function have_run() {
		return $this->microtime_float() - $this->start_time;
	}

	/**
	 * Simple function to replicate PHP 5 behaviour
	 *
	 * @return decimal
	 */
	private function microtime_float() {
		list ($usec, $sec) = explode( ' ', microtime() );

		return ( (float) $usec + (float) $sec );
	}

}
