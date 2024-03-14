<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
/*
Plugin Name: WP Armour - Honeypot Anti Spam
Plugin URI: http://wordpress.org/plugins/honeypot/
Description: Add honeypot anti spam protection.
Author: Dnesscarkey
Version: 2.1.18
Author URI: https://dineshkarki.com.np/wp-armour-anti-spam
*/

include 'includes/wpa_config.php';
include 'includes/wpa_functions.php';
include 'includes/wpa_dashboard_widget.php';
include 'includes/views/wpa_notice.php';

add_action( 'init', function(){
	if( !is_admin() ){ // ONLY BLOCK SPAM IF IT IS NOT ADMIN PANEL
		include 'includes/integration/wpa_bbpress.php';
		include 'includes/integration/wpa_wpcomment.php';
		include 'includes/integration/wpa_wpregistration.php';
		include 'includes/integration/wpa_contactform7.php';		
		include 'includes/integration/wpa_gravityforms.php';
		include 'includes/integration/wpa_formidable.php';
		include 'includes/integration/wpa_calderaforms.php';
		include 'includes/integration/wpa_toolsetform.php';
		include 'includes/integration/wpa_diviform.php';		
	}
	include 'includes/integration/wpa_elementor.php';
	include 'includes/integration/wpa_fluentform.php';	
	include 'includes/integration/wpa_diviengineform.php';
	include 'includes/integration/wpa_wplogin.php';
	include 'includes/integration/wpa_wpforms.php';
});




add_action('wp_enqueue_scripts','wpa_load_scripts');
add_action('login_enqueue_scripts','wpa_load_scripts');
add_action('admin_menu', 'wpa_plugin_menu');
add_action('wpa_handle_spammers','wpa_save_stats',10,2);

register_activation_hook( __FILE__, 'wpa_plugin_activation' );

function wpa_plugin_activation(){
    add_option('wpa_installed_date',date('Ymd'));
    add_option('wpa_field_name',wpa_unqiue_field_name());
    add_option('wpa_error_message',' Spamming or your Javascript is disabled !!');
    add_option('wpa_disable_test_widget','no');    
    add_option('wpa_stats','{"total":{"today":{"date":"'.date('Ymd').'","count":0},"week":{"date":"'.date('Ymd').'","count":0},"month":{"date":"'.date('Ymd').'","count":0},"all_time":0}}');
}