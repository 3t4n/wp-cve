<?php

namespace Upress\Booter;

use Exception;

class Updater {
	protected static $current_version;
	protected static $dbname;
	protected static $collate;
	protected static $wpdb;

	/**
	 * Run any necessary db updates, file upgrades etc.
	 */
	public static function upgrade() {
		global $wpdb;

		self::$wpdb = $wpdb;
		self::$current_version = get_option( 'booter_version' );
		self::$dbname          = self::$wpdb->prefix . BOOTER_404_DB_TABLE;
		self::$collate = self::$wpdb->get_charset_collate();

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		try {
			if ( ! self::$current_version || version_compare( self::$current_version, '1.0', '<' ) ) {
				self::update_1_0();
			}

			if ( version_compare( self::$current_version, '1.2', '<' ) ) {
				self::update_1_2();
			}

			if ( version_compare( self::$current_version, '1.3', '<' ) ) {
				self::update_1_3();
			}

			// make sure we update the version in the database so we can run upgrades at later times
			update_option( 'booter_version', BOOTER_VERSION );
		} catch ( Exception $ex ) {
			error_log( $ex );
			wp_die( $ex->getMessage() );
		}
	}

	/**
	 * Update to the 1.0 version
	 * create the 404 database
	 * @throws Exception
	 */
	protected static function update_1_0() {
		dbDelta( "CREATE TABLE IF NOT EXISTS ". ( self::$dbname ) ." (
			`id` bigint(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			`uid` varchar(191) NOT NULL DEFAULT '',
			`url` text(0) NOT NULL DEFAULT '',
			`hits` int(11) UNSIGNED NOT NULL DEFAULT 0,
			`created_at` datetime(0) NOT NULL DEFAULT '0000-00-00 00:00:00',
			`updated_at` datetime(0) NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY (`id`),
			UNIQUE INDEX (`uid`) USING BTREE
		) ". ( self::$collate ) .";" );

		if ( ! empty( self::$wpdb->last_error ) ) {
			throw new Exception( self::$wpdb->last_error );
		}
	}

	/**
	 * update to 1.2
	 * rename mu plugin file
	 */
	protected static function update_1_2() {
		$mu_dir = ( defined( 'WPMU_PLUGIN_DIR' ) && defined( 'WPMU_PLUGIN_URL' ) ) ? WPMU_PLUGIN_DIR : trailingslashit( WP_CONTENT_DIR ) . 'mu-plugins';
		$mu_dir = untrailingslashit( $mu_dir );

		if ( file_exists( $mu_dir . '/booter-crawlers-manager.php' ) ) {
			rename( $mu_dir . '/booter-crawlers-manager.php' , $mu_dir . '/booter-crawlers-manager-mu.php' );
		}
	}

	/**
	 * Update to 1.3
	 * Make sure the woocommerce blocks are disabled if woocommerce is not installed
	 */
	protected static function update_1_3() {
		$settings = get_option( BOOTER_SETTINGS_KEY );

		if ( 'yes' === $settings['block']['enabled_woocommerce'] ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';

			$woo_active = is_plugin_active( 'woocommerce/woocommerce.php' );
			$settings['block']['enabled_woocommerce'] = $woo_active ? 'yes' : 'no';

			update_option( BOOTER_SETTINGS_KEY, $settings );
		}
	}

}
