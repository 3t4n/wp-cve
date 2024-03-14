<?php

/**
 * Fired during plugin activation
 *
 * @link       https://appcheap.io
 * @since      1.0.0
 *
 */

namespace AppBuilder;

defined( 'ABSPATH' ) || exit;

class Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function activate() {
		// Install database tables.
		self::create_tables();
		self::create_folder();
	}

	/**
	 * Creates database tables which the plugin needs to function.
	 *
	 * @access private
	 * @static
	 * @since  1.0.0
	 * @global $wpdb
	 */
	private function create_tables() {
		global $wpdb;

		$table_name_carts = $wpdb->prefix . APP_BUILDER_CART_TABLE;

		// Disables showing of database errors.
		$wpdb->hide_errors();

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}

		// Queries create carts table
		$table_carts = "CREATE TABLE {$table_name_carts} ( cart_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, blog_id INT NOT NULL, cart_key char(42) NOT NULL, cart_value longtext NOT NULL, cart_expiry BIGINT UNSIGNED NOT NULL, PRIMARY KEY (cart_id), UNIQUE KEY cart_key (cart_key) ) $collate;";

		// Execute
		dbDelta( $table_carts );
	} // END create_tables()

	/**
	 * Create app builder folder
	 */
	private function create_folder() {
		$dir = APP_BUILDER_PREVIEW_DIR;
		if ( ! is_dir( $dir ) ) {
			@mkdir( $dir, 0755 );
		}
		@chmod( $dir, 0755 );
	}
}
