<?php

/*  
 * Robo Maps            http://robosoft.co/wordpress-google-maps
 * Version:             1.0.6 - 19837
 * Author:              Robosoft
 * Author URI:          http://robosoft.co
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * Date:                Thu, 18 May 2017 11:11:10 GMT
 */

class Robo_Maps_Activator {

	public static function activate() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . "robo_maps";
		$ex_t = $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" );
		if ( !$ex_t ) {	
			$sql = "CREATE TABLE $table_name (
			  id mediumint(9) NOT NULL AUTO_INCREMENT,
			  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			  name tinytext NOT NULL,
			  options text NOT NULL,
			  UNIQUE KEY id (id)
			) $charset_collate;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		} 
	}
}
