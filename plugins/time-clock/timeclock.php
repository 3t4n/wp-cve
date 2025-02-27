<?php

/*
Plugin Name: Time Clock
Description: An employee and volunteer time clock plugin for WordPress
Author: Scott Paterson
Author URI: https://wpplugin.org
Version: 1.2.2
Text Domain: etimeclockwp
Domain Path: /languages/
*/


/*  Copyright (c) 2018 - 2023 Scott Paterson

    Employee Timeclock is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    Time Clock is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// define common variables

if (!defined('ETIMECLOCKWP_PLUGIN_PATH')) {
	define('ETIMECLOCKWP_PLUGIN_PATH',			plugin_dir_path(__FILE__));
}
if (!defined('ETIMECLOCKWP_PLUGIN_BASENAME')) {
	define('ETIMECLOCKWP_PLUGIN_BASENAME',		plugin_basename(__FILE__));
}
if (!defined('ETIMECLOCKWP_SITE_URL')) {
	define('ETIMECLOCKWP_SITE_URL',				get_site_url());
}
if (!defined('ETIMECLOCKWP_NAME')) {
	define('ETIMECLOCKWP_NAME', 				'Time Clock');
}
if (!defined('ETIMECLOCKWP_VERSION')) {
	define('ETIMECLOCKWP_VERSION', 				'1.2.2');
}
if (!defined('ETIMECLOCKWP_SETTINGS_PAGE')) {
	define('ETIMECLOCKWP_SETTINGS_PAGE', 		'etimeclockwp_settings_page');
}

// languages
load_plugin_textdomain('etimeclockwp', false, ETIMECLOCKWP_PLUGIN_BASENAME . '/i18n/languages');

// wp version
global $wp_version;

// empty function used by free version to check if pro version is installed
function etimeclockwp_free() {
}

// check if pro version is attempting to be activated - if so, then deactive the free version
if (function_exists('etimeclockwp_pro')) {

	deactivate_plugins('time-clock-pro/timeclock.php');

} else {

	// check plugin requirements
	if ((version_compare(PHP_VERSION, '5.6', '<')) || (version_compare($wp_version, '4.0', '<'))) {
		
		// notices
		add_action( 'admin_notices', create_function( '', "echo '<div class=\"error\"><p>". __('Employee Time Clock requires PHP 5.6+ and WordPress 4.0+ to function properly. Your current configuration does not meet one or more of these requirements.', 'etimeclockwp'). "</p></div>';" ) );
		
		add_action( 'admin_notices', create_function( '', "echo '<div class=\"error\"><p>".__('Employee Time Clock has been auto-deactivated.', 'etimeclockwp') ."</p></div>';" ) );
		
		// deactivate plugin
		function etimeclockwp_deactivate_self() {
			deactivate_plugins(plugin_basename( __FILE__ ));
		}
		add_action('admin_init','etimeclockwp_deactivate_self');
		
		// remove plugin activated notice
		if (isset($_GET['activate'])) {
			unset($_GET['activate']);
		}
		
		return;
		
	} else {
		
		// activate hook
		function etimeclockwp_activation() {
			global $wp_rewrite;
			
			// activate includes
			include_once ('includes/admin/post_types.php');
			
			// register post types and taxonomies
			etimeclockwp_register_post_type();
			
			// save time that plugin was installed
			add_option( 'etimeclockwp_install_date', date('Y-m-d G:i:s'), '', 'yes');
		}
		
		// deactivate hook
		function etimeclockwp_deactivation() {
			delete_option("etimeclockwp_firstrun");
		}
		
		// uninstall hook
		function etimeclockwp_uninstall() {
			
			// remove all plugin data if option is enabled
			if (etimeclockwp_get_option('uninstall') == "1") {
				etimeclockwp_uninstaller();
			}
			
		}		
		
		// register hooks
		register_activation_hook(__FILE__,'etimeclockwp_activation');
		register_deactivation_hook(__FILE__, 'etimeclockwp_deactivation');
		register_uninstall_hook(__FILE__,'etimeclockwp_uninstall');
		
		
		// public includes
		include_once ('includes/admin/post_types.php');
		include_once ('includes/settings/settings_api.php');
		include_once ('includes/enqueue.php');
		include_once ('includes/functions.php');
		include_once ('includes/actions.php');
		include_once ('includes/shortcodes.php');
		
		// get settings
		$etimeclockwp_options = etimeclockwp_get_options();
		
		// admin includes
		if (is_admin()) {
			include_once ('includes/admin/menu.php');
			include_once ('includes/admin/activity.php');
			include_once ('includes/admin/users.php');
			include_once ('includes/admin/menu.php');
			include_once ('includes/admin/extensions.php');
			include_once ('includes/admin/settings/settings_page.php');
			include_once ('includes/admin/settings/settings_dashboard_items.php');
			include_once ('includes/admin/ajax_functions_admin.php');
			include_once ('includes/admin/deactivate_survey.php');
			include_once ('includes/admin/uninstall.php');
		}
	}
}