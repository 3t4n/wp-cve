<?php
/**
 * Contains code for database util class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Util
 */

namespace Boxtal\BoxtalConnectWoocommerce\Util;

use Boxtal\BoxtalConnectWoocommerce\Branding;

/**
 * Database util class.
 *
 * Helper to manage boxtal connect plugin tables.
 */
class Database_Util {

	/**
	 * Create the plugin tables.
	 *
	 * @void
	 */
	public static function create_tables() {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$tables = self::get_schema();
		dbDelta( $tables );
	}

	/**
	 * Get tables schema.
	 *
	 * @return string
	 */
	public static function get_schema() {
		global $wpdb;

		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}

		/*
		 * Indexes have a maximum size of 767 bytes. Historically, we haven't need to be concerned about that.
		 * As of WordPress 4.2, however, we moved to utf8mb4, which uses 4 bytes per character. This means that an index which
		 * used to have room for floor(767/3) = 255 characters, now only has room for floor(767/4) = 191 characters.
		 *
		 * This may cause duplicate index notices in logs due to https://core.trac.wordpress.org/ticket/34870 but dropping
		 * indexes first causes too much load on some servers/larger DB.
		 */
		$max_index_length = 191;

		$tables = "
CREATE TABLE {$wpdb->prefix}" . Branding::$branding_short . "_pricing_items (
    pricing_id int(6) NOT NULL,
    shipping_method_instance varchar(256) NOT NULL,
    price_from float DEFAULT NULL,
    price_to float DEFAULT NULL,
    weight_from float DEFAULT NULL,
    weight_to float DEFAULT NULL,
    shipping_class text NOT NULL,
    parcel_point_network text NULL,
    pricing enum('rate','free','deactivated') NOT NULL,
    flat_rate float DEFAULT NULL,
    UNIQUE KEY pkey (pricing_id,shipping_method_instance($max_index_length))
) $collate;
    ";

		return $tables;
	}
}
