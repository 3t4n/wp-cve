<?php
/**
* Plugin Name: Delivery Countdown Timer 
* Version:1.0
* Author: Arul Jayaraj
* Author URI: http://www.aruljayaraj.com/
* Description: Showing the nextday delivery timer with text based on cut off time.
**/
if (!defined('WP_CONTENT_URL')){
	define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
}
if (!defined('WP_CONTENT_DIR')){
	define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
}
if (!defined('WP_PLUGIN_URL') ){
	define('WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins');
}
if (!defined('WP_PLUGIN_DIR') ){
	define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');
}
if (!defined('CT_PLUGIN_URL') ){
	define('CT_PLUGIN_URL', WP_CONTENT_URL. '/plugins/delivery-countdown-timer');
}
if (!defined('CT_PLUGIN_DIR') ){
	define('CT_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins/delivery-countdown-timer');
}
if (!defined('DS') ){
	define('DS', DIRECTORY_SEPARATOR);
}

if (!defined('CT_PLUGIN_ASSETS_URL') ){
	define('CT_PLUGIN_ASSETS_URL', CT_PLUGIN_URL.'/assets');
}
if (!defined('CT_PLUGIN_HTML_DIR') ){
	define('CT_PLUGIN_HTML_DIR', CT_PLUGIN_DIR.DS.'html');
}

register_activation_hook( __FILE__, array( 'CountdownTimer', 'install' ) );
register_uninstall_hook( __FILE__, array( 'CountdownTimer', 'uninstall' ) );
require_once CT_PLUGIN_DIR.DS.'class'.DS.'class_ct.php';
new CountdownTimer();
?>
