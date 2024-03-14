<?php
/* 
Plugin Name: Data8 Validation
Plugin URI: https://www.data-8.co.uk/resources/support/how-to/wordpress-data8-data-tools
Description: This plugin integrates Data8 email, telephone, name and address validation in WooCommerce, Gravity Forms, WPForms, Elementor Pro and Contact Form 7
Author: Data8
Version: 3.7
Author URI: http://www.data-8.co.uk
WC requires at least: 2.2.3
WC tested up to: 6.1
*/

/*  Copyright 2016 - 2017 Data8 Ltd. All Rights Reserved.
*/

function d8cf7_validation_js(){
	$d8pacf7_script_vars = array(
		'ajaxKey' => get_option('d8cf7_client_api_key'),
		'applicationName' => 'WordPress');

	if(null !== get_option('d8cf7_predictiveaddress_options')){
		$options = explode("\n", str_replace('\"', '"', get_option('d8cf7_predictiveaddress_options')));
		
		foreach ( $options as $option ) {
			if ( strpos($option, ':') !== false ) {
				$optionArr = explode(":", $option, 2);
				$d8pacf7_script_vars[trim($optionArr[0])] = trim($optionArr[1]);
			}
		}
	}

	wp_register_script('d8pa', 'https://webservices.data-8.co.uk/javascript/predictiveaddress.js', null, null, true);
	wp_register_script('d8pacf7', 'https://webservices.data-8.co.uk/javascript/predictiveaddress_cf7.js', array('jquery', 'd8pa'), null, true);
	wp_localize_script('d8pacf7', 'd8pacf7_script_vars', $d8pacf7_script_vars);
	wp_enqueue_script('d8pa');
	wp_enqueue_script('d8pacf7');
	
	wp_register_style('d8cf7_style', 'https://webservices.data-8.co.uk/content/predictiveaddress.css');
	wp_enqueue_style('d8cf7_style');	
}

if (get_option('d8cf7_predictiveaddress'))
	add_action('wp_enqueue_scripts', 'd8cf7_validation_js');

include('plugin_interface.php');

$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'd8cf7_settings_link');
?>