<?php if ( ! defined( 'ABSPATH' ) ) exit; 
/*
	Plugin Name: WP Shopify
	Plugin URI:  https://profiles.wordpress.org/fahadmahmood/#content-plugins
	Description: Display Shopify products on your WordPress blog.
	Version:     1.4.4
	Author:      Fahad Mahmood
	Author URI:  https://profiles.wordpress.org/fahadmahmood/#content-about
	License:     GPL2
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
	Text Domain: wp-shopify
	Domain Path: /languages
*/
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}else{
		 
	}
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	global $wpsy_data, $wpsy_pro;
	
	$wpsy_premium_copy = 'https://shop.androidbubbles.com/product/wp-shopify';
	$wpsy_data = get_plugin_data(__FILE__);
	
	
	define( 'WPSY_PLUGIN_DIR', dirname( __FILE__ ) );
	define( 'WPSY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	
	$wpsy_pro_file = WPSY_PLUGIN_DIR . '/pro/wpsy-pro.php';

	
	
	$wpsy_pro =  file_exists($wpsy_pro_file);
	
	if($wpsy_pro){
		
		include_once($wpsy_pro_file);		
		
	}
		
	$wpsy_data = get_plugin_data(__FILE__);
	

	include_once('inc/functions.php');
	include_once('inc/graphql.php');
	
	if(function_exists('wp_shopify_admin_links')){
		$plugin = plugin_basename(__FILE__); 
		add_filter("plugin_action_links_$plugin", 'wp_shopify_admin_links' );	
	}	