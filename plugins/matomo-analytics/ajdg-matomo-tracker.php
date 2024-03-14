<?php
/*
Plugin Name: AJdG Matomo Tracker
Plugin URI: https://ajdg.solutions/product/matomo-tracker-for-wordpress/?mtm_campaign=ajdg_matomo_tracker
Author: Arnan de Gans
Author URI: https://www.arnan.me/?mtm_campaign=ajdg_matomo_tracker
Description: Easily add the Matomo tracking code to your websites footer and manage options for it from the dashboard.
Version: 1.2.9
Text Domain: ajdg-matomo-tracker
Domain Path: /languages/
License: GPLv3
*/

/* ------------------------------------------------------------------------------------
*  COPYRIGHT NOTICE
*  Copyright 2020-2023 Arnan de Gans. All Rights Reserved.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from its use.
------------------------------------------------------------------------------------ */

defined('ABSPATH') or die();

/*--- Load Files --------------------------------------------*/
$plugin_folder = plugin_dir_path(__FILE__);
require_once($plugin_folder.'/ajdg-matomo-tracker-functions.php');
/*-----------------------------------------------------------*/

/*--- Core --------------------------------------------------*/
register_activation_hook(__FILE__, 'ajdg_matomo_activate');
register_deactivation_hook(__FILE__, 'ajdg_matomo_deactivate');
load_plugin_textdomain('ajdg-matomo-tracker', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');
/*-----------------------------------------------------------*/

/*--- Front end ---------------------------------------------*/
if(!is_admin()) {
	$matomo_active = get_option('ajdg_matomo_active');
	if($matomo_active == 'yes') add_action('wp_footer', 'ajdg_matomo_tracker');
	$matomo_feed_clicks = get_option('ajdg_matomo_track_feed_clicks');
	if($matomo_feed_clicks == 'yes') add_filter('post_link', 'ajdg_matomo_feed_clicks');
	$matomo_feed_impressions = get_option('ajdg_matomo_track_feed_impressions');
	if($matomo_feed_impressions == 'yes') add_filter('the_content', 'ajdg_matomo_feed_impressions');
}
/*-----------------------------------------------------------*/

/*--- Back end ----------------------------------------------*/
if(is_admin()) {
	ajdg_matomo_check_config();
	/*--- Dashboard ---------------------------------------------*/
	add_action('admin_menu', 'ajdg_matomo_dashboard_menu');
	add_action('admin_print_styles', 'ajdg_matomo_dashboard_styles');
	add_action('admin_notices', 'ajdg_matomo_notifications_dashboard');
	add_filter('plugin_action_links_' . plugin_basename( __FILE__ ), 'ajdg_matomo_action_links');
	/*--- Actions -----------------------------------------------*/
	if(isset($_POST['matomo_save'])) add_action('init', 'ajdg_matomo_save_settings');
}
/*-----------------------------------------------------------*/

/*-------------------------------------------------------------
 Name:      ajdg_matomo_dashboard_menu
 Purpose:   Add pages to admin menus
-------------------------------------------------------------*/
function ajdg_matomo_dashboard_menu() {
	add_management_page('Matomo Tracker', 'Matomo Tracker', 'manage_options', 'matomo-tracker', 'ajdg_matomo_info');
}

/*-------------------------------------------------------------
 Name:      ajdg_matomo_info
 Purpose:   Admin general info page
-------------------------------------------------------------*/
function ajdg_matomo_info() {
	$status = $do = '';
	if(isset($_GET['status'])) $status = esc_attr($_GET['status']);

	$current_user = wp_get_current_user();
	?>

	<div class="wrap">
		<h1><?php _e('AJdG Matomo Tracker', 'ajdg-matomo-tracker'); ?></h1>

		<?php
		if($status > 0) ajdg_matomo_status($status);
		include("ajdg-matomo-tracker-dashboard.php");
		?>

		<br class="clear" />
	</div>
<?php
}
?>