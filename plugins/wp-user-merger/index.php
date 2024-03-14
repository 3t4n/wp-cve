<?php if ( ! defined( 'ABSPATH' ) ) exit; 
/*
	Plugin Name: WP User Merger
	Plugin URI: https://profiles.wordpress.org/fahadmahmood/wp-user-merger
	Description: A user friendly plugin to merge multiple user accounts.
	Version: 1.5.7
	Author: Fahad Mahmood
	Author URI: http://androidbubble.com/blog/
	Text Domain: wp-user-merger
	Domain Path: /languages
	License: GPL2	
	
	This WordPress plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version. This WordPress plugin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should have received a copy of the GNU General Public License	along with this WordPress plugin. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
*/


	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}else{
		 clearstatcache();
	}
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
	$wpus_all_plugins = get_plugins();
	$wpus_active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
	
	//if ( array_key_exists('woocommerce/woocommerce.php', $wpus_all_plugins) && in_array('woocommerce/woocommerce.php', $wpus_active_plugins) ) {
		
		
	
	
	global $wpus_data, $wpus_pro, $wpus_activated, $yith_pre_order, $wpus_premium_link, $wpus_url, $wpsu_options;
	
	$wpus_premium_link = 'https://shop.androidbubbles.com/product/wp-user-merger';//https://shop.androidbubble.com/products/wordpress-plugin?variant=36439508779163';//
	
	$yith_pre_order = (in_array( 'yith-pre-order-for-woocommerce/init.php',  $wpus_active_plugins) || in_array( 'yith-woocommerce-pre-order.premium/init.php',  $wpus_active_plugins));
	
	$wpus_activated = true;
	
	$wpus_url = plugin_dir_url( __FILE__ );
	$wpus_data = get_plugin_data(__FILE__);

	$wpsu_options = get_option('wpsu_options', array());
	
	
	define( 'WPUS_PLUGIN_DIR', dirname( __FILE__ ) );
	
	$wpus_pro_file = WPUS_PLUGIN_DIR . '/pro/wpus-pro.php';
	$wpus_pro =  file_exists($wpus_pro_file);
	require_once WPUS_PLUGIN_DIR . '/inc/functions.php';
	
	if($wpus_pro)
	include_once($wpus_pro_file);		
	
	if(is_admin()){
		add_action( 'admin_menu', 'wpus_admin_menu' );	
		
		if(function_exists('wpus_plugin_links')){
			$plugin = plugin_basename(__FILE__); 
			add_filter("plugin_action_links_$plugin", 'wpus_plugin_links' );	
		}			
		
		
	}
	
		
	//}