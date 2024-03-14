<?php
/**
 * The functionality of the plugin responsible of storing and refreshing the addresses cache.
 *
 * @link  https://cargus.ro/
 * @since 1.0.0
 *
 * @package Cargus
 * @subpackage Cargus/public
 */

define( 'CACHE_DIR', plugin_dir_path( __FILE__ ) . 'locations/' );
define( 'FILE_THRESHOLD_SECONDS', 24 * 3600 );
/**
 * The functionality of the plugin responsible for storing and refreshing the addresses cache.
 *
 * It creates and checks the plugin addresses cache files.
 *
 * @package Cargus
 * @subpackage Cargus/public
 * @author Cargus <contact@cargus.ro>
 */
class Cargus_Cache {

	/**
	 * Initialize the class.
	 */
	public function __construct() {
		clearstatcache();

		$this->load_dependencies();
		// check if cache dir is usable.
		// create dir.
		if ( ! is_dir( CACHE_DIR ) && ! mkdir( CACHE_DIR, 0775 ) ) {
			$msg = __CLASS__ . '::' . __FUNCTION__ . ' Unable to create cache dir: ' . CACHE_DIR;
			$this->write_log( $msg );
		}

		if ( ! is_writable( CACHE_DIR ) ) {
			$msg = __CLASS__ . '::' . __FUNCTION__ . ' Unable to write in cache dir: ' . CACHE_DIR;
			$this->write_log( $msg );
		}
	}

	/**
	 * Include the cargus shipping method class.
	 *
	 * @since    1.0.0
	 */
	public function load_dependencies() {

		/**
		 * The class responsible for creating the Cargus api methods.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cargus-api.php';
	}

	/**
	 * Write error logs in the WordPress error log.
	 *
	 * @param String $log The error log.
	 */
	private function write_log( $log ) {
		if ( true === WP_DEBUG ) {
			if ( is_array( $log ) || is_object( $log ) ) {
				error_log( print_r( $log, true ) );
			} else {
				error_log( $log );
			}
		}
	}

	/**
	 * Write error logs in the WordPress error log.
	 *
	 * @param string $filename The file name.
	 * @param bool   $also_expired If the file experd or not.
	 */
	public function get_cached_file( $filename, $also_expired = false ) {
		clearstatcache();

		$file = CACHE_DIR . $filename . '.json';

		// check if file is present in cache dir.
		if ( ! file_exists( $file ) ) {
			return false;
		}

		// check if expired or updating.
		$elapsed = time() - filemtime( $file );
		if ( $elapsed >= FILE_THRESHOLD_SECONDS && ! $also_expired ) {
			return false;
		}

		// get data.
		$data = file_get_contents( $file );

		if ( false === $data ) {
			$msg = __CLASS__ . '::' . __FUNCTION__ . " Error reading file: $file";
			$this->write_log( $msg );

			return false;
		}

		if ( strlen( $data ) <= 2 ) {
			$msg = __CLASS__ . '::' . __FUNCTION__ . " File was empty: $file, data=$data";
			$this->write_log( $msg );

			// try to remove file.
			if ( ! unlink( $file ) ) {
				$msg = __CLASS__ . '::' . __FUNCTION__ . " Unable to delete file: $file";
				$this->write_log( $msg );
			}

			return false;
		}

		return $data;
	}

	/**
	 * Write error logs in the WordPress error log.
	 *
	 * @param string $filename The file name.
	 * @param string $json The json to be written in to the file.
	 */
	public function write_cache_file( $filename, $json ) {
		clearstatcache();

		$file = CACHE_DIR . $filename . '.json';

		// Write the file.
		$fp = @fopen( $file, 'w' );

		if ( false === $fp ) {
			$msg = __CLASS__ . '::' . __FUNCTION__ . " Unable to write file: $file";
			$this->write_log( $msg );

			return false;
		}

		$status = true;

		if ( false === fwrite( $fp, $json ) ) {
			$msg = __CLASS__ . '::' . __FUNCTION__ . " Writing to file $file failed, data=$json";
			$this->write_log( $msg );

			$status = false;
		}

		fclose( $fp );

		return $status;
	}

	/**
	 * Get the cargus streets.
	 *
	 * @param string $city The city id.
	 */
	public function get_streets( $city = null ) {
		if ( is_null( $city ) ) {
			return '[]';
		}

		// get file from cache.
		$json = $this->get_cached_file( 'str' . $city );

		if ( false === $json ) {
			// file not found in cache.

			// get fresh data.
			$cargus = new Cargus_Api();

			$data = $cargus->get_streets( $city );

			$json = wp_json_encode( $data );

			// save data to cache.
			$this->write_cache_file( 'str' . $city, $json );
		}

		// return json data.
		return $json;
	}

	/**
	 * Get the cargus counties.
	 */
	public function get_counties() {
		// get file from cache.
		$json = $this->get_cached_file( 'counties' );

		if ( false === $json ) {
			// file not found in cache.

			// get fresh data.
			$cargus = new Cargus_Api();
			$data   = $cargus->get_counties();
			$json   = wp_json_encode( $data );

			// save data to cache.
			$this->write_cache_file( 'counties', $json );
		}

		// return data.
		return json_decode( $json, true );
	}

	/**
	 * Get the cargus localities.
	 *
	 * @param string $county_id The city id.
	 */
	public function get_localities( $county_id = null ) {
		if ( is_null( $county_id ) ) {
			return array();
		}

		// get file from cache.
		$json = $this->get_cached_file( 'localities' . $county_id );

		if ( false === $json ) {
			// file not found in cache.

			// get fresh data.
			$cargus = new Cargus_Api();
			$data   = $cargus->get_localities( $county_id );
			$json   = wp_json_encode( $data );

			// save data to cache.
			$this->write_cache_file( 'localities' . $county_id, $json );
		}

		// return json data.
		return $json;
	}
}
