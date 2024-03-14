<?php
/**
 * Fired during plugin activation
 *
 * @since      1.0
 *
 * @package    Maintenance_Page
 * @subpackage Maintenance-Page/includes/lib
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Maintenance_Page
 * @subpackage Maintenance-Page/includes
 * @author     ThemeGrill <themegrill@gmail.com>
 */
class Maintenance_Page_Activator {

	/**
	 * creates database table on activation
	 *
	 * @global WPDB $wpdb
	 *
	 * @since    1.0.0
	 */
	public static function activate( $networkwide ) {
		global $wpdb;

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			//check if it is network activation if so run the activation function for each id
			if ( $networkwide ) {
				$old_blog = $wpdb->blogid;
				//Get all blog ids
				$blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

				foreach ( $blogids as $blog_id ) {
					switch_to_blog( $blog_id );
					//Create database table if not exists
					self::create_subscribe_table();
				}
				switch_to_blog( $old_blog );
				return;
			}
		}
		//Create database table if not exists
		self::create_subscribe_table();
	}

	/**
	 * sql query for creating database table
	 *
	 * @global WPDB $wpdb
	 *
	 * @since    1.0.0
	 */
	public static function create_subscribe_table() {
		global $wpdb;
		$query = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}maintenance_page (
         `id` bigint(20) NOT NULL AUTO_INCREMENT,
         `email` varchar(50) NOT NULL UNIQUE,
         `insert_date` datetime NOT NULL,
         PRIMARY KEY (`id`)
      ) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $query );
	}
}
