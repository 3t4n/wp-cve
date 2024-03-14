<?php
/*
* Plugin Name: Add Google re captcha in Wordpress Forms
* Description: Added Google re-CAPTCHA in Wordpress in any form like comment form, login form, forgot password form, woocommerce form etc. 
* Version: 1.3
* Author: Sky Web Development
* Tested up to: 5.3
* Author URI: https://www.skywebdevelopment.in
* License: GPL3
* Text Domain: wp-google-recaptcha
*/

if (!defined('ABSPATH')) {
	die( 'Access Disabled' );
}

function re_captcha_plugin_action_link_by_sky($links) {
	return array_merge(array("settings" => "<a href=\"options-general.php?page=google_recaptcha-options\">".__("Settings", "wp-google-recaptcha")."</a>"), $links);
}
add_filter("plugin_action_links_".plugin_basename(__FILE__), "re_captcha_plugin_action_link_by_sky");

define( 'GRCW_Path_Set', plugin_dir_path( __FILE__ ) );
include( GRCW_Path_Set . 'inc.php');
