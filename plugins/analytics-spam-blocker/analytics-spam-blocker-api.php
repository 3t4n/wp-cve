<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT NOTICE
*  Copyright 2016-2023 Arnan de Gans. All Rights Reserved.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from its use.
------------------------------------------------------------------------------------ */

defined('ABSPATH') or die();

/*-------------------------------------------------------------
 Name:      AJdG Solutions API Library
---------------------------------------------------------------
 Changelog:
---------------------------------------------------------------
 Mar 18, 2022 - 3.0
 * Now uses API3
 
 Dec/12/2018 - 2.0
 * Better security
 * Faster responses
 * Stats sharing
 
 Oct/25/2016 - 1.0
 * Initial version
-------------------------------------------------------------*/

function asb_report_submit() {
	if(wp_verify_nonce($_POST['asb_nonce_report'], 'asb_nonce_report')) {
		$report = array('name' => '', 'email' => '', 'votes' => '', 'domain' => '');
		if(isset($_POST['asb_report_username'])) $report['name'] = $_POST['asb_report_username'];
		if(isset($_POST['asb_report_email'])) $report['email'] = $_POST['asb_report_email'];
		if(isset($_POST['asb_report_votes'])) $report['votes'] = $_POST['asb_report_votes'];
		if(isset($_POST['asb_report_domain'])) $report['domain'] = $_POST['asb_report_domain'];

		// Cleanup
		$report['name'] = sanitize_text_field(trim($report['name'], "\t\n "));		
		$report['email'] = sanitize_email(trim(strtolower($report['email'])));
		$report['votes'] = esc_attr(trim($report['votes']));
		$report['domain'] = esc_url_raw(trim(strtolower($report['domain']), "\t\n "));
		$report['domain'] = parse_url($report['domain']);
		$report['domain'] = $report['domain']['host'];
		$report['domain'] = rtrim($report['domain'], '%20'); // Experimental

		// Process and response
		if(strlen($report['name']) < 1 OR strlen($report['email']) < 1 OR strlen($report['domain']) < 1) {
			asb_return(400); // Incomplete form
		} else if(!preg_match("/^([-a-z0-9]{2,253})\.([a-z\.]{2,63})$/", $report['domain'])) {
			asb_return(401); // Invalid domain
		} else if(!preg_match("/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,63})$/", $report['email'])) {
			asb_return(402); // Invalid email
		} else {
			$domains = get_option('ajdg_spamblocker_domains');	
			$domains['updated'] = current_time('timestamp');
			if(!in_array($report['domain'], $domains['domains'])) {
				$domains['domains'][] = $report['domain'];
			}
			
			update_option('ajdg_spamblocker_domains', $domains);

			asb_edit_htaccess();
			
			asb_api_response($report);
			exit;
		}
	} else {
		asb_nonce_error();
		exit;
	}
}

function asb_api_response($report = false) {
	if(!function_exists('get_plugins')) require_once ABSPATH . 'wp-admin/includes/plugin.php';
	$plugins = get_plugins();
	$plugin_version = $plugins['analytics-spam-blocker/analytics-spam-blocker.php']['Version'];

	$user_instance = get_option('ajdg_spamblocker_user');
	$request = array('slug' => 'analytics-spam-blocker', 'instance' => (!empty($user_instance)) ? $user_instance : 'register',  'platform' => get_option('siteurl'), 'report' => $report, 'et' => microtime(true));
	$args = array('headers' => array('Accept' => 'multipart/form-data'), 'body' => array('r' => serialize($request)), 'user-agent' => 'Analytics Spam Blocker/'.$plugin_version.';', 'sslverify' => false, 'timeout' => 5);

	$response = wp_remote_post('https://ajdg.solutions/api/spam-blocker/3/', $args);

    if(!is_wp_error($response)) {
		$data = json_decode($response['body'], 1);

		if(empty($data['instance'])) $data['instance'] = 0;
		if(empty($data['report_id'])) $data['report_id'] = 0;
		if(empty($data['stats'])) $data['stats'] = array();

		update_option('ajdg_spamblocker_user', $data['instance']);
		update_option('ajdg_spamblocker_stats', $data['stats']);

		if($data['report_id'] > 0) {
			asb_return(200);
		} else {
			asb_return(201);
		}
		exit;
	} else {
		asb_return(500, array('error' => $response['response']['code'].': '.$response['response']['message']));
		exit;
	}
}

function ajdg_api_stats_update() {
	asb_api_response();
}
?>