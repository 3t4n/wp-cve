<?php

/**
 * Fired during plugin uninstall
 *
 * @link       https://webmuehle.at
 * @since      1.1.2
 *
 * @package    Courtres
 * @subpackage Courtres/includes
 */

/**
 * Fired during plugin uninstall.
 *
 * This class defines all code necessary to run during the plugin's uninstall.
 *
 * @since      1.1.2
 * @package    Courtres
 * @subpackage Courtres/includes
 * @author     Webmühle e.U. <office@webmuehle.at>
 */
class Courtres_Uninstaller {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.1.2
	 */
	public static function uninstall() {
		remove_role( 'player' );

		$role = get_role( 'administrator' );
		$role->remove_cap( 'place_reservation', true );

		// remove tables
		global $wpdb;

		// courts table
		$table_name = $wpdb->prefix . 'courtres_settings';
		$sql        = "DROP TABLE IF EXISTS $table_name";
		$wpdb->query( $sql );

		// reservations table
		$table_name = $wpdb->prefix . 'courtres_reservations';
		$sql        = "DROP TABLE IF EXISTS $table_name";
		$wpdb->query( $sql );

		// events table
		$table_name = $wpdb->prefix . 'courtres_events';
		$sql        = "DROP TABLE IF EXISTS $table_name";
		$wpdb->query( $sql );

		// courts table
		$table_name = $wpdb->prefix . 'courtres_courts';
		$sql        = "DROP TABLE IF EXISTS $table_name";
		$wpdb->query( $sql );
	}
}
