<?php
/**
 * Plugin activation
 * Create database tables if not exist.
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
 * Wp_Custom_Cursors_Activator
 *
 * @package    Wp_Custom_Cursors
 * @subpackage Wp_Custom_Cursors/includes
 * @author     Hamid Reza Sepehr <hamidsepehr4@gmail.com>
 */
class Wp_Custom_Cursors_Activator {

	/**
	 * Activator function
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		if ( current_user_can( 'manage_options' ) ) {
			global $wpdb;
			global $charset_collate;
			$added_cursors_table = $wpdb->prefix . 'added_cursors';
			if ( $wpdb->get_var( "SHOW TABLES LIKE '$added_cursors_table'" ) != $added_cursors_table ) {
				require_once ABSPATH . 'wp-admin/includes/upgrade.php';
				$sql_create_table = "CREATE TABLE `$added_cursors_table` (
					cursor_id bigint(20) unsigned NOT NULL auto_increment,
					cursor_type varchar(20) NOT NULL default 'shape',
					cursor_shape varchar(20) NOT NULL default '0',
					default_cursor varchar(20) NOT NULL default 'none',
					color longtext NULL,
					width bigint(20) unsigned NOT NULL default '30',
					blending_mode varchar(20) NOT NULL default 'normal',
					hide_tablet varchar(20) NOT NULL default 'on',
					hide_mobile varchar(20) NOT NULL default 'on',
					hide_admin varchar(20) NOT NULL default 'on',
					activate_on bigint(20) unsigned NOT NULL default '0',
					selector_type varchar(20) NOT NULL default 'tag',
					selector_data varchar(50) NOT NULL default 'body',
					PRIMARY KEY  (cursor_id),
					KEY cursor_type (cursor_type)
				    ) $charset_collate; ";
				dbDelta( $sql_create_table );
			}

			$created_cursors_table = $wpdb->prefix . 'created_cursors';
			if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE '%s'", $created_cursors_table ) ) != $created_cursors_table ) {
				require_once ABSPATH . 'wp-admin/includes/upgrade.php';
				$sql_create_table = "CREATE TABLE `$created_cursors_table` (
					cursor_id bigint(20) unsigned NOT NULL auto_increment,
					cursor_type varchar(20) NOT NULL default 'shape',
					cursor_options longtext NULL,
					hover_cursors longtext NULL,
					PRIMARY KEY  (cursor_id),
					KEY cursor_type (cursor_type)
				    ) $charset_collate; ";
				dbDelta( $sql_create_table );
			}
		}
	}
}
