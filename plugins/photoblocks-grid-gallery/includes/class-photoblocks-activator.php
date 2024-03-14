<?php

/**
 * Fired during plugin activation
 *
 * @link       https://machothemes.com
 * @since      1.0.0
 *
 * @package    Photoblocks
 * @subpackage Photoblocks/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Photoblocks
 * @subpackage Photoblocks/includes
 * @author     MachoThemes <office@machothemes.net>
 */
class Photoblocks_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		global $photoblocks_db_version;

		if ( is_multisite() ) {

			foreach ( $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" ) as $blog_id ) {
				switch_to_blog( $blog_id );
				$table_name = $wpdb->prefix . 'photoblocks';

				$charset_collate = $wpdb->get_charset_collate();

				$sql = "CREATE TABLE $table_name (
					id mediumint(9) NOT NULL AUTO_INCREMENT,
					created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					updated datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					name tinytext NOT NULL,
					data LONGTEXT NULL,
					blocks LONGTEXT NULL,
					PRIMARY KEY  (id)
				) $charset_collate;";

				require_once ABSPATH . 'wp-admin/includes/upgrade.php';
				dbDelta( $sql );

				add_option( 'photoblocks_db_version', $photoblocks_db_version );
				restore_current_blog();
			}
		} else {

			$table_name = $wpdb->prefix . 'photoblocks';

			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				updated datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				name tinytext NOT NULL,
				data LONGTEXT NULL,
				blocks LONGTEXT NULL,
				PRIMARY KEY  (id)
			) $charset_collate;";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );

			add_option( 'photoblocks_db_version', $photoblocks_db_version );
		}
	}

}
