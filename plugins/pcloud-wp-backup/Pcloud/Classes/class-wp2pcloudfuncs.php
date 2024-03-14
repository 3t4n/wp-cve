<?php
/**
 * WP2PcloudFuncs class
 *
 * @file class-wp2pcloudfuncs.php
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes;

/**
 * Class WP2PcloudFuncs
 */
class WP2PcloudFuncs {

	/**
	 * Try to set high execution limits
	 */
	public static function set_execution_limits() {

		if ( function_exists( 'memory_limit' ) && wp_is_ini_value_changeable( 'memory_limit' ) ) {
			/**
			 * We will try to increase the memory limit to "unlimited".
			 *
			 * @noinspection PhpUndefinedFunctionInspection
			 */
			memory_limit( - 1 );
		}
		if ( function_exists( 'ignore_user_abort' ) ) {
			ignore_user_abort( true );
		}
		if ( function_exists( 'set_time_limit' ) ) {
			set_time_limit( 0 );
		}

		if ( function_exists( 'ini_get' ) ) {
			if ( false === wp_is_ini_value_changeable( 'memory_limit' ) ) {
				$current_limit = ini_get( 'memory_limit' );
				if ( ! defined( 'WP_MEMORY_LIMIT' ) ) {
					define( 'WP_MEMORY_LIMIT', $current_limit );
				}
			} elseif ( is_multisite() ) {
				if ( ! defined( 'WP_MEMORY_LIMIT' ) ) {
					define( 'WP_MEMORY_LIMIT', '256M' );
				}
			} else {
				if ( ! defined( 'WP_MEMORY_LIMIT' ) ) {
					define( 'WP_MEMORY_LIMIT', '256M' );
				}
			}
		}
	}

	/**
	 * Provides tha pCloud API endpoint hostname depending on the selected by user datacenter.
	 *
	 * @return string
	 */
	public static function get_api_ep_hostname(): string {
		$location = intval( self::get_storred_val( PCLOUD_API_LOCATIONID, '1' ) );
		if ( $location < 1 ) {
			$location = 1;
		}

		if ( 1 === $location ) {
			$wp2pcl_api_server = 'api.pcloud.com';
		} else {
			$wp2pcl_api_server = 'eapi.pcloud.com';
		}

		return $wp2pcl_api_server;
	}

	/**
	 * Get storred value in WP options system
	 *
	 * @param string          $key Storred item key, must be a string.
	 * @param string|int|null $default Item default value.
	 *
	 * @return int
	 */
	public static function get_storred_val( string $key, $default = '' ) {

		$key = trim( $key );

		$test_val = get_option( $key, false );
		if ( is_bool( $test_val ) && ! $test_val ) {
			add_option( $key, $default );

			return $default;
		}

		return $test_val;
	}

	/**
	 * Set storred value in WP options system
	 *
	 * @param string $key Storred item key, must be a string.
	 * @param mixed  $value Storred item value.
	 */
	public static function set_storred_val( string $key, $value ) {

		$key = trim( $key );

		$test_val = get_option( $key, false );
		if ( is_bool( $test_val ) && ! $test_val ) {
			add_option( $key, strval( $value ) );
		} else {
			if ( $test_val !== $value ) {
				update_option( $key, strval( $value ) );
			}
		}
	}

	/**
	 * Get operation
	 *
	 * @return array
	 */
	public static function get_operation(): array {

		$opration_json = self::get_storred_val( PCLOUD_OPERATION );
		if ( is_bool( $opration_json ) || empty( $opration_json ) ) {
			$opration_json = '{"operation": "nothing", "state": "sleep"}';
		}

		$resp_arr = json_decode( $opration_json, true );
		if ( ! is_array( $resp_arr ) ) {
			$resp_arr = array(
				'operation' => 'nothing',
				'state'     => 'sleep',
			);
		}

		return $resp_arr;
	}

	/**
	 * Set operation
	 *
	 * @param array|null $operation_data Array with current operational data, can be empty.
	 *
	 * @return void
	 */
	public static function set_operation( ?array $operation_data = array() ) {

		if ( count( $operation_data ) < 1 || empty( $operation_data ) ) {
			$operation_data = array(
				'operation' => 'nothing',
				'state'     => 'sleep',
			);
		}

		if ( isset( $operation_data['state'] ) && 'sleep' === $operation_data['state'] ) {
			$operation_data['cleanat'] = time() + 5;
		}

		$waiting_async_items = self::get_storred_val( PCLOUD_ASYNC_UPDATE_VAL );
		if ( ! empty( $waiting_async_items ) ) {

			$items_to_update = json_decode( $waiting_async_items, true );
			if ( is_array( $items_to_update ) ) {
				foreach ( $items_to_update as $k => $v ) {
					$operation_data[ $k ] = $v;
				}
			}

			self::set_storred_val( PCLOUD_ASYNC_UPDATE_VAL, '' ); // Maximum number of failures.
		}

		$json_data = wp_json_encode( $operation_data );

		self::set_storred_val( PCLOUD_OPERATION, $json_data );
	}

	/**
	 * Add item for async update as a setting.
	 *
	 * @param string $key Key of the setting.
	 * @param mixed  $value The value of the setting.
	 *
	 * @return void
	 */
	public static function add_item_for_async_update( string $key, $value ) {

		if ( empty( $key ) ) {
			return;
		}

		$items_to_update = array();

		$waiting_items = self::get_storred_val( PCLOUD_ASYNC_UPDATE_VAL );
		if ( ! empty( $waiting_items ) ) {
			$items_to_update = json_decode( $waiting_items, true );
		}

		$items_to_update[ $key ] = $value;

		$json_data = wp_json_encode( $items_to_update );

		self::set_storred_val( PCLOUD_ASYNC_UPDATE_VAL, $json_data );
	}

	/**
	 * Format bytes to human-readable format
	 *
	 * @param string|int $bytes Bytes to be made more human-readable.
	 *
	 * @return string
	 */
	public static function format_bytes( $bytes ): string {
		$size   = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' );
		$factor = floor( ( strlen( $bytes ) - 1 ) / 3 );

		return sprintf( '%.2f', $bytes / pow( 1024, $factor ) ) . $size[ $factor ];
	}

	/**
	 * Get the current memory usage allocated to this PHP process and convert it to human-readable.
	 *
	 * @return string
	 */
	public static function memory_usage(): string {
		$memlimitini = ini_get( 'memory_limit' );

		$mem = memory_get_usage();
		if ( $mem > 0 ) {
			return 'mem: ' . self::format_bytes( $mem ) . '/' . $memlimitini;
		} else {
			return 'mem: --';
		}
	}
}
