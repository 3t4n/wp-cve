<?php
namespace CTXFeed\V5\Utility;
class Cache {
	/**
	 * Get Cached Data
	 *
	 * @param string $key Cache Name
	 *
	 * @return mixed|false  false if cache not found.
	 * @since 3.3.10
	 */
	public static function get( $key ) {
		if ( empty( $key ) ) {
			return false;
		}

		return get_transient( '__woo_feed_cache_' . $key );
	}

	/**
	 * Set Cached Data
	 *
	 * @param string $key Cache name. Expected to not be SQL-escaped. Must be
	 *                             172 characters or fewer.
	 * @param mixed $data Data to cache. Must be serializable if non-scalar.
	 *                             Expected to not be SQL-escaped.
	 * @param int|bool $expiration Optional. Time until expiration in seconds. Default 0 (no expiration).
	 *
	 * @return bool
	 */
	public static function set( $key, $data, $expiration = false ) {
		if ( empty( $key ) ) {
			return false;
		}

		if ( false === $expiration ) {
			$expiration = get_option( 'woo_feed_settings', array( 'cache_ttl' => 6 * HOUR_IN_SECONDS ) );
			$expiration = (int) $expiration['cache_ttl'];
		}

		return set_transient( '__woo_feed_cache_' . $key, $data, $expiration );
	}

	public static function delete( $key ) {
		if ( empty( $key ) ) {
			return false;
		}

		return delete_transient( '__woo_feed_cache_' . $key );

	}

	/**
	 * Delete All Cached Data
	 *
	 * @return bool
	 */
	public static function flush() {
		global $wpdb;

		return $wpdb->query( "DELETE FROM $wpdb->options WHERE ({$wpdb->options}.option_name LIKE '_transient_timeout___woo_feed_cache_%') OR ({$wpdb->options}.option_name LIKE '_transient___woo_feed_cache_%')" ); // phpcs:ignore
	}
}