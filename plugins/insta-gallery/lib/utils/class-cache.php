<?php

namespace QuadLayers\IGG\Utils;

/**
 * QuadLayers Cache
 * Version: 1.0.1
 * Date: 24/11/2022
 */

/**
 * Cache Class
 */
class Cache {

	/**
	 * Disable cache test
	 *
	 * @var boolean
	 */
	private static $disable_cache_test = false;

	/**
	 * Force expiration test
	 *
	 * @var boolean
	 */
	private static $force_expiration_test = false;

	/**
	 * Cache class prefix
	 *
	 * @var string
	 */
	private static $prefix = 'qligg_cache_';

	/**
	 * Cache mininum expiration lapse
	 *
	 * @var integer
	 */
	public static $min_expiration_lapse_in_hours = 6;

	/**
	 * Cache autoexpires setting
	 *
	 * @var boolean
	 */
	private static $autoexpires;

	/**
	 * User set cache expiration lapse
	 *
	 * @var int
	 */
	public static $expiration_lapse_in_hours;

	/**
	 * Current time in timestamp type
	 *
	 * @var int
	 */
	public static $current_time_timestamp;

	/**
	 * Cache dynamic prefix
	 *
	 * @var string
	 */
	public $dynamic_prefix = '';

	/**
	 * Class constructor
	 *
	 * @param integer $expiration_lapse_in_hours User set expiration lapse in hours.
	 * @param boolean $autoexpires Property to define if cache autoexpire.
	 * @param string  $add_prefix Prefix to use as dynamic prefix.
	 */
	public function __construct( int $expiration_lapse_in_hours, $autoexpires, $add_prefix ) {

		$this->dynamic_prefix = $add_prefix;

		self::$expiration_lapse_in_hours = max( self::$min_expiration_lapse_in_hours, absint( $expiration_lapse_in_hours ) );

		self::$autoexpires            = $autoexpires;
		self::$current_time_timestamp = current_time( 'timestamp' );
	}

	/**
	 * Function to get complete cache prefix
	 *
	 * @return string
	 */
	public function get_prefix() {

		return static::$prefix . $this->dynamic_prefix;
	}

	/**
	 * Get the expiration date
	 *
	 * @param int $cache_timestamp Timestamp to use to calculate cache expiration.
	 * @return int
	 */
	public function get_cache_expiration_timestamp( int $cache_timestamp ) {
		if ( self::$force_expiration_test ) {
			return 0;
		}
		return $cache_timestamp + self::$min_expiration_lapse_in_hours * HOUR_IN_SECONDS;
	}

	/**
	 * Return true if a date is expired, false if not
	 *
	 * @param int $cache_timestamp Timestamp to use to check if cache is expired.
	 * @return boolean
	 */
	public function is_cache_expired( int $cache_timestamp ) {
		// retorna true si se vencio, false si no se vencio
		/**
		 * Conditional to hardcode function return true
		 */
		if ( ! self::$disable_cache_test ) {
			return self::$current_time_timestamp > $this->get_cache_expiration_timestamp( $cache_timestamp ); // Funcion
		}
		return true;
	}

	/**
	 * Get the url key to access to database
	 *
	 * @param string $url Url to get database url key.
	 * @return string
	 */
	public function get_db_url_key( string $url ) {
		return $this->get_prefix() . '_' . md5( $url );
	}

	/**
	 * Get option from database
	 *
	 * @param string $url Url to get cached data.
	 * @return array
	 */
	public function get( $url ) {

		$cache_option_key = $this->get_db_url_key( $url );

		$cache = '';

		if ( static::$autoexpires ) {
			$cache = get_transient( $cache_option_key );
		} else {
			$cache = get_option( $cache_option_key, false );
		}

		if ( ! isset( $cache['timestamp'] ) || $this->is_cache_expired( $cache['timestamp'] ) ) {
			return array();
		}

		return $cache;
	}

	/**
	 * Update option in database
	 *
	 * @param string $url Url to use as key.
	 * @param array  $response Response data to be updated.
	 * @return void
	 */
	public function update( $url, $response ) {

		$cache_option_key = $this->get_db_url_key( $url );

		$cache = array(
			'response'  => $response,
			'timestamp' => current_time( 'timestamp' ),
		);

		if ( static::$autoexpires ) {
			set_transient( $cache_option_key, $cache, static::$expiration_lapse_in_hours * 3600 );
		} else {
			update_option( $cache_option_key, $cache );
		}
	}

	/**
	 * Delete option from database
	 *
	 * @param string $url Url to use as key.
	 * @return void
	 */
	public function delete_key( $url ) {
		$cache_option_key = $this->get_db_url_key( $url );

		if ( static::$autoexpires ) {
			delete_transient( $cache_option_key );
		} else {
			delete_option( $cache_option_key );
		}

	}

	/**
	 * Delete all data saved from database that are related to class prefix
	 *
	 * @return void
	 */
	public function delete() {
		global $wpdb;

		$search_prefix = '%' . $this->get_prefix() . '%';

		$tks = $wpdb->get_results( $wpdb->prepare( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE %s", $search_prefix ) );

		if ( $tks ) {
			foreach ( $tks as $key => $name ) {
				if ( static::$autoexpires ) {
					delete_transient( str_replace( '_transient_', '', $name->option_name ) );
				} else {
					delete_option( $name->option_name );
				}
			}
		}
	}
}
