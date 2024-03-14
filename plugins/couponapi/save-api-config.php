<?php

/*******************************************************************************
 *
 *  Copyrights 2017 to Present - Sellergize Web Technology Services Pvt. Ltd. - ALL RIGHTS RESERVED
 *
 * All information contained herein is, and remains the
 * property of Sellergize Web Technology Services Pvt. Ltd.
 *
 * The intellectual and technical concepts & code contained herein are proprietary
 * to Sellergize Web Technology Services Pvt. Ltd. (India), and are covered and protected
 * by copyright law. Reproduction of this material is strictly forbidden unless prior
 * written permission is obtained from Sellergize Web Technology Services Pvt. Ltd.
 * 
 * ******************************************************************************/
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function couponapi_save_api_config() {
	
	$message = '';
	if( wp_verify_nonce($_POST['config_nonce'], 'couponapi') ) {

		global $wpdb;
		$wp_prefix = $wpdb->prefix;
		
		$autopilot = ((isset($_POST['autopilot']) AND sanitize_text_field($_POST['autopilot']) == 'on')? 'On':'Off');
		$API_KEY = sanitize_key(trim($_POST['API_KEY']));
		$last_extract_date = esc_sql(sanitize_text_field($_POST['last_extract_date']));
		$last_extract_time = esc_sql(sanitize_text_field($_POST['last_extract_time']));

		if(!empty($last_extract_date) and !empty($last_extract_time)) {
			$last_extract = strtotime($last_extract_date . ' ' . $last_extract_time) - get_option('gmt_offset')*60*60;
		} else {
			$last_extract = null;
		}
		
		// Validations
		if($autopilot=='On' and empty($API_KEY)) {
			$message .= '<div class="notice notice-error is-dismissible"><p>'.__("API Key is required for Auto-Pilot.","couponapi").'</p></div>';
			
		} else {

			if(empty($last_extract) and !empty($API_KEY)) {
				if(!empty($usage['last_extract_ts'])) {
					$last_extract = $usage['last_extract_ts'];
				} else {
					$last_extract = strtotime('2001-01-01 00:00:00') - get_option('gmt_offset')*60*60;
				}
			}
			$sql = "REPLACE INTO ".$wp_prefix."couponapi_config (name,value) VALUES
								('autopilot','$autopilot'),
								('API_KEY','$API_KEY'),
								('last_extract','$last_extract')";
			if($wpdb->query($sql) === false) {
				$message .= '<div class="notice notice-error is-dismissible"><p>'.$wpdb->last_error.'</p></div>';
			} else {
				$message .= '<div class="notice notice-success is-dismissible"><p>'.__("Settings saved successfully.","couponapi").'</p></div>';
			}
			if($autopilot == 'On' and !wp_next_scheduled('couponapi_pull_incremental_feed_event')) {
				wp_schedule_event( time(), 'hourly', 'couponapi_pull_incremental_feed_event' );
				$message .= '<div class="notice notice-warning is-dismissible"><p><b>'.__("NOTE","couponapi").':</b>'.__(" This plugin makes use of WordPress scheduling. WordPress does NOT have a real cron scheduler. Instead, it triggers events only when someone visits your website, after the scheduled time has passed. If you currently do not have traffic on your WordPress site, you will need to load a few pages yourself to keep the offers updated.","couponapi").'</p></div>';
			}
			
			if($autopilot == 'Off' and wp_next_scheduled('couponapi_pull_incremental_feed_event')) {
				wp_clear_scheduled_hook('couponapi_pull_incremental_feed_event');
			}
		}
		
	} else {
		$message .= '<div class="notice notice-error is-dismissible"><p>Access Denied. Nonce could not be verified.</p></div>';
	}
	
	setcookie('message',$message);
	wp_redirect('admin.php?page=couponapi');
	exit;
}

?>
