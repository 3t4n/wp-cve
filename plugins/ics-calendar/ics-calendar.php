<?php
/*
Plugin Name: ICS Calendar
Plugin URI: https://icscalendar.com
Description: Turn your Google Calendar, Microsoft Office 365 or Apple iCloud Calendar into a seamlessly integrated, auto-updating, zero-maintenance WordPress experience.
Version: 10.14.1.3
Author: Room 34 Creative Services, LLC
Author URI: https://icscalendar.com
License: GPL2
Text Domain: r34ics
Domain Path: /i18n/languages/
*/

/*
  Copyright 2024 Room 34 Creative Services, LLC (email: info@room34.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/


// Don't load directly
if (!defined('ABSPATH')) { exit; }


// Load required files
require_once(plugin_dir_path(__FILE__) . 'class-r34ics.php');
require_once(plugin_dir_path(__FILE__) . 'functions.php');
require_once(plugin_dir_path(__FILE__) . 'r34ics-ajax.php');


// Backward compatibility for WP < 5.3
if (!function_exists('wp_date')) {
	require_once(plugin_dir_path(__FILE__) . 'compatibility.php');
}


// Initialize plugin functionality
add_action('plugins_loaded', 'r34ics_plugins_loaded');
function r34ics_plugins_loaded() {

	// Instantiate class
	global $R34ICS;
	$R34ICS = new R34ICS();
	
	// Load text domain
	load_plugin_textdomain('r34ics', false, basename(plugin_dir_path(__FILE__)) . '/i18n/languages/');
	
	// Conditionally run update function
	if (is_admin() && version_compare(get_option('r34ics_version'), $R34ICS->version, '<')) { r34ics_update(); }
	
}


// Install
register_activation_hook(__FILE__, 'r34ics_install');
function r34ics_install() {
	global $R34ICS;

	// Flush rewrite rules
	flush_rewrite_rules();
	
	// Remember previous version
	$previous_version = get_option('r34ics_version');
	update_option('r34ics_previous_version', $previous_version);
	
	// Set version
	if (isset($R34ICS->version)) {
		update_option('r34ics_version', $R34ICS->version);
	}
	
	// New installation; write option to use new defaults
	if (empty($previous_version)) {
		update_option('r34ics_use_new_defaults_10_6', true);
	}

	// Prepare deferred admin notices
	$notices = get_option('r34ics_deferred_admin_notices', array());

	// Admin notice with link to settings
	$notices['r34ics_first_load'] = array(
		'content' => '<p>' . sprintf(__('Thank you for installing %1$s. Before creating your first calendar shortcode, please visit your %2$sGeneral Settings%3$s page and verify that your site language, timezone and date/time format settings are correct. See our %4$sUser Guide%5$s for more information.', 'r34ics'), '<strong>ICS Calendar</strong>', '<a href="' . admin_url('options-general.php') . '">', '</a>', '<a href="https://icscalendar.com/general-wordpress-settings/" target="_blank">', '</a>') . '</p>',
		'status' => 'info',
	);
	
	// Save deferred admin notices
	update_option('r34ics_deferred_admin_notices', $notices);
}


// Updates
function r34ics_update() {
	global $R34ICS;

	// Remember previous version
	$previous_version = get_option('r34ics_version');
	update_option('r34ics_previous_version', $previous_version);
	
	// Update version
	if (isset($R34ICS->version)) {
		update_option('r34ics_version', $R34ICS->version);
	}
	
	// Version-specific updates
	// v. 6.11.1 renamed option from 'r34ics_transient_expiration' to 'r34ics_transient_expiration' so it's not a transient itself
	if (version_compare($previous_version, '6.11.1', '<')) {
		$transients_expiration = get_option('r34ics_transient_expiration') ? get_option('r34ics_transient_expiration') : 3600;
		update_option('r34ics_transients_expiration', $transients_expiration);
		delete_option('r34ics_transient_expiration');
	}
	
	// v. 10.7.1 replaces "Load JS and CSS files on wp_enqueue_scripts action" option with check for block themes
	// Block themes support conditionally enqueuing JS and CSS when the page contains the ICS Calendar shortcode
	if (version_compare($previous_version, '10.7.1', '<')) {
		delete_option('r34ics_load_css_js_on_wp_enqueue_scripts');
	}

	// Prepare deferred admin notices
	$notices = get_option('r34ics_deferred_admin_notices', array());

	// Admin notice about new default options
	if (version_compare($previous_version, '10.6.0', '<')) {
		$notices['r34ics_new_parameter_defaults_10_6'] = array(
			'content' => '<p>' . sprintf(__('%1$sPlease note:%2$s %3$s version 10.6 changes the default options for several shortcode settings. In order to maintain consistency, these new defaults are %4$snot%5$s enabled when upgrading from an earlier version. If you would like to learn more about the changes please read our %6$sblog post%7$s, or to switch to the new defaults, turn on the %8$s option on the %9$s settings%10$s page.', 'r34ics'), '<strong>', '</strong>', 'ICS Calendar', '<em>', '</em>', '<a href="https://icscalendar.com/updated-parameter-defaults-in-ics-calendar-10-6/" target="_blank">', '</a>', '<strong>' . __('Use new parameter defaults (v.10.6)', 'r34ics') . '</strong>', '<a href="' . esc_url(r34ics_get_admin_url('settings')) . '">ICS Calendar', '</a>') . '</p>',
			'status' => 'info',
		);
	}
	else {
		unset($notices['r34ics_new_parameter_defaults_10_6']);
	}

	// Save deferred admin notices
	update_option('r34ics_deferred_admin_notices', $notices);

	// Purge calendar transients
	r34ics_purge_calendar_transients();
	
}


// Deferred install/update admin notices
add_action('admin_notices', 'r34ics_deferred_admin_notices');
function r34ics_deferred_admin_notices() {
	if ($notices = get_option('r34ics_deferred_admin_notices', array())) {
		foreach ((array)$notices as $notice) {
			echo '<div class="notice notice-' . esc_attr($notice['status']) . ' is-dismissible r34ics-admin-notice"><div>' . wp_kses_post($notice['content']) . '</div></div>';
		}
	}
	delete_option('r34ics_deferred_admin_notices');
}


// Purge transients on certain option updates
add_action('update_option_timezone_string', 'r34ics_purge_calendar_transients');
