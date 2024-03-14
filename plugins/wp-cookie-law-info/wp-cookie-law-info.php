<?php
/**
 * Plugin Name: WP Cookie Law Info
 * Plugin URI: https://wordpress.org/plugins/wp-cookie-law-info/
 * Description: A simple way to show EU Cookie Law in your website.
 * Version: 1.1
 * Author: Soham Web Solution
 * Author URI: https://sohamsolution.com/
 * Text Domain: wp-cookie-law-info
 * License: GPL2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// define constant value 
define('WCL_VERSION', '1.1');
define('WCL_FILE', basename(__FILE__));
define('WCL_PATH', plugin_dir_path(__FILE__));
define('WCL_URL', plugin_dir_url(__FILE__));
define('WCL_TEXTDOMAIN', 'wp-cookie-law-info');


// Plugin avivation hook for add plugin settings in wp_option table. 
register_activation_hook( __FILE__, 'wcl_plugin_activate_callback' );
function wcl_plugin_activate_callback() {
   	$wcl_option = array(
		'_enable' 		  			=> '1',
		'_position'			  		=> 'bottom',
		'_theme'  					=> 'block',
		'_popup_bgcolor' 			=> '#000000',
		'_popup_txtcolor' 			=> '#ffffff',
		'_popup_message' 			=> 'This website uses cookies to ensure you get the best experience on our website.',
		'_btn_bgcolor' 				=> '#f1d600',
		'_btn_txtcolor' 			=> '#000000',
		'_btn_lable' 				=> 'Got it!',
		'_policy_lable' 			=> 'Learn More',
		'_policy_url' 				=> home_url(),
	);
	add_option( 'wcl_settings', $wcl_option );
}


// Plugin enqueue style and script 
add_action('wp_enqueue_scripts', 'wcl_wp_enqueue_scripts');
function wcl_wp_enqueue_scripts() {
	$wcl_option 	= get_option( 'wcl_settings' );
	$cookie_enable 	= $wcl_option['_enable'];
	if ($cookie_enable == '1'){
		wp_enqueue_style( 'wcl-style', plugins_url('assets/css/wcl.min.css',__FILE__));
		wp_enqueue_script( 'wcl-js', plugins_url('assets/js/jquery.wcl.min.js',__FILE__), array('jquery'), WCL_VERSION, true );
	}
}


// Add setting link in plugin listing page.
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wcl_setting_plugin_action_links_callback' );
function wcl_setting_plugin_action_links_callback( $links ) {
if(is_admin()){
   $links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=wp-cookie-law-settings') ) .'" target="_blank">Cookie Law Settings</a>';
   $links[] = '<a href="http://www.paypal.me/anshulgangrade" target="_blank">Donate Me</a>';
   }
   return $links;
}


// require files. 
require_once( WCL_PATH . '/admin/admin-setting.php' );
require_once( WCL_PATH . '/view/show-cookie-popup.php' );
