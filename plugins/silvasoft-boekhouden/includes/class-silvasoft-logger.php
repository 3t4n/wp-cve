<?php

/**
 * Write log lines to the Silvasoft log table
 *
 * @link       silvasoft.nl
 * @since      1.0.0
 *
 * @package    Silvasoft
 * @subpackage Silvasoft/includes
 */

class Silvasoft_Logger {

	public function __construct() {
		//no need
	}
	
	/* Write a log line to the database */
	public function doLog($subject,$message,$woo_order_id = NULL,$canresend = false,$creditnota=false) {
	 	global $wpdb;
		$table_name = $wpdb->prefix . "silvasoft_woo_log"; 
		$wpdb->insert( 
			$table_name, 
			array( 
				'time' => current_time( 'mysql' ), 
				'name' => $subject, 
				'text' => $message, 
				'woo_order_id'=>$woo_order_id,
				'creditorder' => $creditnota,
				'canresend'=>$canresend,
			) 
		);
	}
}
