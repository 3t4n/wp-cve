<?php defined('ABSPATH') or die("No script kiddies please!");
/*
Plugin Name: Uptime Robot Plugin for Wordpress
Plugin URI: https://wordpress.org/plugins/uptime-robot-monitor/
Description: View your uptime stats/logs within WordPress (dashboard), and if desired on pages, posts or in a widget.
Author: Aphotrax
Text Domain: urpro
Domain Path: /languages
Version: 2.3
Author URI: https://aphotrax.eu/services/uptime-robot-wordpress-plugin/?utm_source=WordPress&utm_medium=plugins&utm_campaign=plugin
*/

	include_once(plugin_dir_path( __FILE__ )."functions.php");

define("urpro_vers", '2.2.2');

function urpro_activate(){
	$plugin_version = urpro_vers;
	global $wpdb;
	$table_name = $wpdb->base_prefix . 'urpro';
    if(get_site_option('urpro_version') != $plugin_version OR $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name){
	include_once(plugin_dir_path( __FILE__ )."activate.php");
	urpro_forceactivate($plugin_version);
    }
}
function urpro_delete(){
	include_once(plugin_dir_path( __FILE__ )."delete.php");
}
function urpro_admin(){
 if (current_user_can('manage_options'))  {
	include_once(plugin_dir_path( __FILE__ )."admin-dashboard.php");
	include_once(plugin_dir_path( __FILE__ )."admin-settings.php");
   if(urpro_data("apikey","no") != ""){
	include_once(plugin_dir_path( __FILE__ )."admin-shortcodes.php");
	include_once(plugin_dir_path( __FILE__ )."admin-monitors.php");
	include_once(plugin_dir_path( __FILE__ )."admin-logs.php");
	include_once(plugin_dir_path( __FILE__ )."admin-responsetimes.php");
	include_once(plugin_dir_path( __FILE__ )."admin-styling.php");
   }
 }
}
function urpro_register_shortcodes(){
	include_once(plugin_dir_path( __FILE__ )."shortcodes.php");
}
function urpro_adminmenu(){
	if (current_user_can('manage_options')){
		add_menu_page('Uptime Robot Pro', 'Uptime Robot', 'manage_options', 'urpro-settings', 'urpro_admin_general', 'dashicons-clock');
		add_submenu_page('urpro-settings', __('General', 'urpro'), __('General', 'urpro'), 'manage_options', 'urpro-settings', 'urpro_admin_general');
	}
	if(urpro_data("apikey","no") != ""){
		add_submenu_page('urpro-settings', __('Monitors', 'urpro'), __('Monitors', 'urpro'), 'manage_options', 'urpro-monitors', 'urpro_admin_monitors');
		add_submenu_page('urpro-settings', __('Log history', 'urpro'), __('Log history', 'urpro'), 'manage_options', 'urpro-logs', 'urpro_admin_logs');
		add_submenu_page('urpro-settings', __('Response times', 'urpro'), __('Response times', 'urpro'), 'manage_options', 'urpro-responsetimes', 'urpro_admin_responsetimes');
		add_submenu_page('urpro-settings', __('Styling', 'urpro'), __('Styling', 'urpro'), 'manage_options', 'urpro-styling', 'urpro_admin_styling');
		add_submenu_page('urpro-settings', __('Shortcodes', 'urpro'), __('Shortcodes', 'urpro'), 'manage_options', 'urpro-shortcodes', 'urpro_admin_shortcodes');
	}
}

function urpro_clear_cache(){
	include_once(plugin_dir_path( __FILE__ )."admin-clearcache.php");
}

function urpro_scripts() {
	if(isset($_GET['page']) AND $_GET['page'] == "urpro-monitors"){
    		wp_enqueue_script('jquery-ui-core');
    		wp_enqueue_script('jquery-ui-sortable');
    		wp_enqueue_script('jquery-interface');
	}
}

function urpro_textdomain() {
  load_plugin_textdomain( 'urpro', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

register_uninstall_hook( __FILE__, 'urpro_delete');
add_action( 'init', 'urpro_register_shortcodes');
add_action( 'admin_init', 'urpro_admin' );
add_action( 'admin_init', 'urpro_admin_notice' );
add_action( 'admin_menu', 'urpro_adminmenu' );
add_action( 'urpro_schedule_clear_cache', 'urpro_clear_cache' );
add_action( 'admin_enqueue_scripts', 'urpro_scripts' );
add_action('plugins_loaded', 'urpro_textdomain');
add_action('plugins_loaded', 'urpro_activate');
add_action( 'wp_dashboard_setup', 'urpro_dashboard');