<?php

/**
 * Fired during plugin activation
 *
 * @link       silvasoft.nl
 * @since      1.0.0
 *
 * @package    Silvasoft
 * @subpackage Silvasoft/includes
 */


class Silvasoft_Activator {

	/**
	 * Setup tables during activation.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		global $silva_db_version;


	   	$table_name = $wpdb->prefix . "silvasoft_woo_log"; 
	   	$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		  name tinytext NOT NULL,
		  text text NOT NULL,
		  canresend BOOLEAN NOT NULL DEFAULT false,
		  creditorder BOOLEAN NOT NULL DEFAULT false,
		  woo_order_id BIGINT(20),
		  PRIMARY KEY  (id)
		) $charset_collate;";
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		
		
		$welcome_name = 'Bedankt!';
		$welcome_text = 'Bedankt voor het gebruiken van de Silvasoft WooCommerce plugin.';
		
		$wpdb->insert( 
			$table_name, 
			array( 
				'time' => current_time( 'mysql' ), 
				'name' => $welcome_name, 
				'text' => $welcome_text, 
			) 
		);
		
		add_option( 'silva_db_version', $silva_db_version );		
		
		if (! wp_next_scheduled ( 'silvasoft_woo_cron' )) {
			wp_schedule_event(time(), 'silvasoftwoosync', 'silvasoft_woo_cron');	
		}
		
	}

}
