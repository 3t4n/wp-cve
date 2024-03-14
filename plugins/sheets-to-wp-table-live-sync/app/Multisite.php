<?php
/**
 * Responsible for plugin compatibility with WordPress multisite.
 *
 * @since 2.12.15
 * @package SWPTLS
 */

namespace SWPTLS;

// If direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Plugin multisite support.
 *
 * @since 2.12.15
 */
class Multisite {

	/**
	 * Class constructor.
	 *
	 * @since 2.12.15
	 */
	public function __construct() {
		add_action( 'wp_initialize_site', [ $this, 'on_create_site' ] );
		add_filter( 'wpmu_drop_tables', [ $this, 'on_delete_site' ] );
	}

	/**
	 * Run on create single site.
	 *
	 * @param object $site The single site instance.
	 * @since 2.12.15
	 */
	public function on_create_site( $site ) {
		if ( is_plugin_active_for_network( 'sheets-to-wp-table-live-sync/sheets-to-wp-table-live-sync.php' ) ) {
			switch_to_blog( (int) $site->blog_id );
				swptls()->database->migration->create_tables();
				swptls()->database->migration->create_tabs();
				swptls()->database->migration->create_license();
			restore_current_blog();
		}
	}

	/**
	 * Run on delete single site.
	 *
	 * @param array $tables Site database tables.
	 * @since 2.12.15
	 */
	public function on_delete_site( $tables ) {
		global $wpdb;
		$tables['gswpts_tables'] = $wpdb->prefix . 'gswpts_tables';
		$tables['gswpts_tabs']   = $wpdb->prefix . 'gswpts_tabs';
		return $tables;
	}
}
