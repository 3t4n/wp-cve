<?php  if ( ! defined( 'ABSPATH' ) ) exit; 
/*
Plugin Name: WP Datepicker
Plugin URI: http://androidbubble.com/blog/wordpress/plugins/wp-datepicker
Description: WP Datepicker is a great plugin to implement custom styled jQuery UI datepicker site-wide. You can set background images and manage CSS from your theme.
Version: 2.0.8
Author: Fahad Mahmood
Author URI: https://www.androidbubbles.com
Text Domain: wp-datepicker
Domain Path: /languages/
License: GPL2

This WordPress Plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version. This free software is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should have received a copy of the GNU General Public License along with this software. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
*/ 


        
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        

	global $wpdp_premium_link, $wpdp_dir, $wpdp_url, $wpdp_pro, $wpdp_data, $wpdp_options, $wpdp_styles, $wpdp_android_settings, $wpdp_actual_link, $wpdp_new_js, $wpdp_new_cs, $wpdp_gen_file, $wpdp_global_settings;
	$wpdp_dir = plugin_dir_path( __FILE__ );
	$wpdp_url = plugin_dir_url( __FILE__ );
	$rendered = FALSE;
	$wpdp_pro = file_exists($wpdp_dir.'pro/wp-datepicker-pro.php');
	$wpdp_data = get_plugin_data(__FILE__);
	$wpdp_premium_link = 'https://shop.androidbubbles.com/product/wp-datepicker-pro';//https://shop.androidbubble.com/products/wordpress-plugin?variant=36439508287643';//
	
	$HTTP_HOST = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : (string)parse_url(get_option('siteurl'), PHP_URL_HOST);
	$wpdp_actual_link = $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$HTTP_HOST$_SERVER[REQUEST_URI]";
	//$wpdp_actual_link = $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$wpdp_actual_link = rtrim($wpdp_actual_link, '/');
    $wpdp_actual_link = strtok($wpdp_actual_link, '?');
	
	
    $wpdp_new_js = 'js/wpdp_auto_script.js';
    $wpdp_new_css = 'css/wpdp_auto_style.css';
    $wpdp_gen_file = false;

	$wpdp_global_settings = get_option('wpdp_global_settings');
	$wpdp_global_settings = is_array($wpdp_global_settings)?$wpdp_global_settings:array();	

//    echo $wpdp_actual_link;exit;

	$wpdp_options = array(
		'dateFormat'=>'text',
		'defaultDate'=>'text'
	);
		
	$wpdp_data = get_plugin_data(__FILE__);
	
	$wpdp_styles = array('faizan-e-madina', 'll-skin-melon', 'll-skin-latoja', 'll-skin-santiago', 'll-skin-lugo', 'll-skin-cangas', 'll-skin-vigo', 'll-skin-nigran', 'll-skin-siena', 'wp-mechanic');//, 'custom-colors');
	
	
	if($wpdp_pro){
		include($wpdp_dir.'pro/wp-datepicker-pro.php');
	}
	
	include('inc/functions.php');
        
	include('io/functions-inner.php');
	$rest_api_url = 'wpdp-android-settings/v1';
	$wpdp_android_settings = new QR_Code_Settings_WPDP($wpdp_dir, $wpdp_url, $rest_api_url);	
		
	add_action( 'admin_enqueue_scripts', 'register_wpdp_scripts' );
	add_action( 'wp_enqueue_scripts', 'register_wpdp_scripts' );
	

	
	if(is_admin()){
				
		
		add_action( 'admin_menu', 'wpdp_menu' );		
		$plugin = plugin_basename(__FILE__); 
		add_filter("plugin_action_links_$plugin", 'wpdp_plugin_links' );	
		
//		add_action('admin_footer', 'wpdp_footer_scripts');
		
	}else{
		
	
//		add_action('wp_footer', 'wpdp_footer_scripts');
		
	}
	
	if(!function_exists('wpdp_plugin_activation')){
	
		function wpdp_plugin_activation(){
			
			global $wpdp_new_css, $wpdp_new_js;
			
			$wpdp_new_js = 'js/wpdp_auto_script.js';
			$wpdp_new_css = 'css/wpdp_auto_style.css';
			
			if(function_exists('wpdp_generate_js_file')){
				wpdp_generate_js_file();
			}
			
			if(function_exists('wpdp_generate_css_file')){
				wpdp_generate_css_file();
			}
		
		
		}
	}		
	register_activation_hook( __FILE__, 'wpdp_plugin_activation' );