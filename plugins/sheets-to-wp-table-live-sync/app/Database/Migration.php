<?php
/**
 * Managing database operations for tables.
 *
 * @since 3.0.0
 * @package SWPTLS
 */

namespace SWPTLS\Database;

// If direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Manages plugin database operations.
 *
 * @since 3.0.0
 */
class Migration {

	/**
	 * Create plugins required database table for tables.
	 *
	 * @param int $network_wide The network wide site id.
	 * @since 2.12.15
	 */
	public function run( $network_wide ) {
		global $wpdb;
		if ( is_multisite() && $network_wide ) {
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );
				$this->create_tables();
				$this->create_tabs();
				$this->create_license();
				restore_current_blog();
			}
		} else {
			$this->create_tables();
			$this->create_tabs();
			$this->create_license();
		}
	}

	/**
	 * Create plugins required database table for tables.
	 *
	 * @since 2.12.15
	 */
	public function create_tables() {
		global $wpdb;

		$collate = $wpdb->get_charset_collate();
		$table   = $wpdb->prefix . 'gswpts_tables';

		$sql = 'CREATE TABLE IF NOT EXISTS ' . $table . ' (
            `id` INT(255) NOT NULL AUTO_INCREMENT,
            `table_name` VARCHAR(512) DEFAULT NULL,
            `source_url` LONGTEXT,
            `source_type` VARCHAR(255),
            `table_settings` LONGTEXT,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB ' . $collate . '';

		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	/**
	 * Create plugins required database table for tabs.
	 *
	 * @since 2.12.15
	 */
	public function create_tabs() {
		global $wpdb;
		$collate = $wpdb->get_charset_collate();
		$table   = $wpdb->prefix . 'gswpts_tabs';

		$sql = 'CREATE TABLE IF NOT EXISTS ' . $table . ' (
            `id` INT(255) NOT NULL AUTO_INCREMENT,
            `tab_name` VARCHAR(512) NOT NULL,
            `show_name` BOOLEAN,
            `reverse_mode` BOOLEAN,
            `tab_settings` LONGTEXT NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB ' . $collate . '';

		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	/**
	 * Create license required multisite.
	 *
	 * @since 2.12.15
	 */
	public function create_license() {
		add_option( 'active_plugins', [] );
	}
}
