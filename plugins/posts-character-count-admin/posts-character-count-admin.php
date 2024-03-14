<?php

/***
 * Plugin Name: Posts Character Count Admin
 * Plugin URI:  http://wordpress.org/plugins/posts-character-count-admin/
 * Text Domain: posts-character-count-admin
 * Domain Path: /languages
 * Description: Displays a column with the character count for each post in the Manage Posts SubPanel and in the Edit Posts SubPanel.
 * Version:     2.1
 * License:     GPLv3
 * Author:      Jan Teriete
 * Author URI:  http://cms.interiete.net/
 * Last Change: 07/18/2014
 *
 * ------------------------------------------------------------------------------------
 * ACKNOWLEDGEMENT
 * ------------------------------------------------------------------------------------
 * This plugin was originally developed by Tanja Preuße
 * http://www.officetrend.de/
 * ------------------------------------------------------------------------------------
 */

// Make sure we don't expose any info if called directly
defined( 'ABSPATH' ) or die( "No script kiddies please!" );

if ( ! class_exists( 'Posts_Character_Count_Admin' ) ) {
	load_plugin_textdomain(
		'posts-character-count-admin',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages/'
	);

	require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'inc/class-posts-character-count.php';
	require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'inc/class-posts-character-count-admin.php';

	add_action( 'plugins_loaded', array( 'Posts_Character_Count_Admin', 'init' ) );
}