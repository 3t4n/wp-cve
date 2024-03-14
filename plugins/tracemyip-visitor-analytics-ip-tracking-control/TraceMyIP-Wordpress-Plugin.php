<?php
/*
	Plugin Name: An Official TraceMyIP Tracker with email alerts
	Plugin URI: https://www.tracemyip.org
	Description: Website visitor IP address activity tracking, IP analytics, visitor email alerts, IP changes tracker and visitor IP address blocking. Tag visitors IPs, track, create email alerts, control and manage pages, links and protect contact forms. GDPR compliant. For visitor tracker setup instructions, see <a href="admin.php?page=tmip_lnk_wp_settings"><b>plugin settings</b></a>.
	Version: 2.56
	Author: TraceMyIP.org
	Author URI: https://www.TraceMyIP.org
	Text Domain: tracemyip-visitor-analytics-ip-tracking-control
	License: GPLv2 (or later)
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
if (!defined('TMIP_VERSION') ) 		define('TMIP_VERSION', '2.56');

### SET CONSTANTS ############################################
// header('X-XSS-Protection:0');
$script_filename=trim($_SERVER['SCRIPT_FILENAME']);
define("tmip_plugin_path",plugin_dir_path( __FILE__ ));
require_once(tmip_plugin_path.'languages/en.php');

define("tmip_plugin_dir_name", 			'tracemyip-visitor-analytics-ip-tracking-control', false);
define("tmip_enable_meta_rating", 		2); 	// Show rate section. 1-post selected rating, 2-show transitional screen
define("tmip_codes_usage_rate_thresh",	50); 	// Number of tracker pageloads required to trigger rating panel
define("tmip_html_to_js_format_realti", 1); 	// 1- Enable real time HTML to JavaScript code conversion
define("tmip_html_to_js_format_onsubm", 1); 	// 1- Enable reformatting html code to JS code on submit of new code or if realtime
												// html>JS have occured at least once
define("tmip_codes_usage_stats_data",	1);		// 1- Enable codes loading status, 2- Enable code usage process counts

define("tmip_trk_path_str_array",		array('vLg','tracker')); // tracker path kneedle. First listed is used for generator 083122075843
define("tmip_trk_add_async_attr",		1); 	// add async attribute for tracker script if not present

### ADD PRE REQ ############################################
require_once(tmip_plugin_path.'includes/functions.php');
tmip_static_urls();
tmip_plugins_dirpath(__FILE__);
tmip_get_url_vars();
// register_activation_hook( __FILE__, 'tmip_func_on_activation' );

### ADD OPTIONS ############################################
add_option(tmip_visit_tracker_val, 		tmip_visit_tracker_default);
add_option(tmip_page_tracker_val, 		tmip_page_tracker_default);

### ADD PLUGIN WP MENU ACTION LINKS ############################################
add_filter('plugin_action_links_'.plugin_basename (__FILE__), 'tmip_plugin_action_links');

### ADD PLUGIN LIST PAGE ROW LINKS ############################################
if (tmip_enable_meta_rating) add_filter( 'plugin_row_meta', 'tmip_plugin_row_add_rating', 2, 2 );

// Determine how plugin is loaded
global $WP_admin_pages;
$WP_admin_pages=NULL;
if (stristr($script_filename,'admin.php')) {
	$WP_admin_pages='admin';
} elseif (stristr($script_filename,'plugins.php')) {
	$WP_admin_pages='plugins';
} elseif (stristr($script_filename,'options-general.php')) {
	$WP_admin_pages='options-general';
}

### ADD USER ACCESS ############################################
add_action('admin_menu', 	'tmip_access_reports');
add_action('admin_menu' , 	'add_tmip_option_page');
add_action('admin_menu', 	'tmip_admin_menu');
add_action('wp_head', 		'tmip_addToTags');


### FUNCTIONS ############################################

// Reset settings
//tmip_reset_plugin_settings();

// Add Page Tracker to header
add_action('wp_head','tmip_insert_page_tracker');

// Add Visitor Tracker to header or footer
$tmip_code_body_position=get_option(tmip_position_val);
tmip_embed_body_html(array($tmip_code_body_position=>'tmip_insert_visitor_tracker'));

?>