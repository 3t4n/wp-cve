<?php
/*
 * Plugin Name: Block IPs for Gravity Forms
 * Plugin URI: https://wordpress.org/plugins/gf-block-ips/
 * Description: Prevent specific IP addresses from submitting form requests created with Gravity Forms.
 * Version: 1.0.2
 * Author: Bright Plugins
 * Requires PHP: 7.2.0
 * Requires at least: 4.0
 * Tested up to: 6.3
 * Author URI: http://brightplugins.com/
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'BV_GF_Block_Ips' ) && !class_exists( 'BV_Callback' ) && !class_exists( 'BV_Links' ) ) {

	define( 'BV_GF_BLOCK_IPS_FULL_NAME', plugin_basename( __FILE__ ) );

	$bv_files = array(
		__DIR__ . '/classes/bv-links.php',
		__DIR__ . '/classes/bv-callback.php',
		__DIR__ . '/classes/bv-gf-block-ips.php',
		__DIR__ . '/includes/bv-functions.php',
	);

	foreach ( $bv_files as $row ) {
		if ( !file_exists( $row ) ) {return;}
		require_once $row;
	}

	new BV_GF_Block_Ips();
}