<?php
/**
 * Plugin deactivation
 * Remove database tables if exist.
 * php version 7.2
 *
 * @category   Plugin
 * @package    Wp_Custom_Cursors
 * @subpackage Wp_Custom_Cursors/includes
 * @author     Hamid Reza Sepehr <hamidsepehr4@gmail.com>
 * @license    GPLv2 or later (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @link       https://hamidrezasepehr.com/
 * @since      2.1.0
 */

/**
 * Wp_Custom_Cursors_Deactivator
 *
 * @package    Wp_Custom_Cursors
 * @subpackage Wp_Custom_Cursors/includes
 * @author     Hamid Reza Sepehr <hamidsepehr4@gmail.com>
 */
class Wp_Custom_Cursors_Deactivator {

	/**
	 * Deactivator function
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		global $wpdb;
		$added_cursors_table   = $wpdb->prefix . 'added_cursors';
		$created_cursors_table = $wpdb->prefix . 'created_cursors';
		$added_cursors_sql     = $wpdb->prepare( 'DROP TABLE IF EXISTS %i', $added_cursors_table );
		$created_cursors_sql   = $wpdb->prepare( 'DROP TABLE IF EXISTS %i', $created_cursors_table );
		$wpdb->query( $added_cursors_sql );
		$wpdb->query( $created_cursors_sql );
	}
}
