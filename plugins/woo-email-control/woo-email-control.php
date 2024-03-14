<?php
/*
Plugin Name: Woo Email Control
Plugin URI: http://www.findshorty.com
Description: Gives you more control over your Woocommerce notification emails. Includes the ability to test your emails with live orders.
Version: 1.061
Author: Ian Young
Author URI: http://www.findshorty.com
Text Domain: wooctrl
Domain Path: /languages
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uploads_dir = wp_upload_dir();
$cache_folder = $uploads_dir['basedir'].'/wooctrl_cache/';

define( 'WOOCTRL_DIR', plugin_dir_url(__FILE__));
define( 'WOOCTRL_PATH', plugin_dir_path(__FILE__));		
define( 'WOOCTRL_TEXTDOMAIN' , 'wooctrl' );
define( 'WOOCTRL_CACHE', $cache_folder);

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	
	require_once('classes/class-wooctrl.php');
	$WCTRL = new WOO_CTRL;
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $WCTRL, 'add_plugin_action_link') );
	
	// create our image cache folder
	function wooctrl_activate() {
		global $cache_folder;
		if ( ! file_exists( $cache_folder ) ) {
			wp_mkdir_p( $cache_folder );
		}
		register_uninstall_hook( __FILE__, 'wooctrl_uninstall' );
	}
	
	// remove our image cache folder and options
	function wooctrl_uninstall() {
    	if( file_exists( $cache_folder) ) {
			unlink($cache_folder);
		}
		global $wpdb;
		$q = 'DELETE FROM '.$wpdb->options.' WHERE option_name LIKE %s';
		$wpdb->query($wpdb->prepare($q, '%'.$wpdb->esc_like('wooctrl_').'%'));
	}
	
	register_activation_hook( __FILE__, 'wooctrl_activate' );
	
}