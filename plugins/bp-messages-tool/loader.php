<?php
/*
Plugin Name: BP Messages Tool
Plugin URI: https://www.philopress.com
Description: View Messages for any BuddyPress member via wp-admin screen Tools > BP Messages
Version: 2.2
Author: PhiloPress
Author URI: https://www.philopress.com/
Text Domain: bpmt
Domain Path: /languages/
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function bpmt_bp_check() {
	if ( !class_exists('BuddyPress') )
		add_action( 'admin_notices', 'bpmt_install_buddypress_notice' );
}
add_action('plugins_loaded', 'bpmt_bp_check', 999);


function bpmt_install_buddypress_notice() {
	echo '<div id="message" class="error fade"><p style="line-height: 150%">';
	_e('<strong>BP Messages Tool</strong></a> requires the BuddyPress plugin. Please <a href="https://buddypress.org/download">install BuddyPress</a> first, or <a href="plugins.php">deactivate BP Messages Tool</a>.');
	echo '</p></div>';
}

function bpmt_init() {

	if( is_admin() ) {

		load_plugin_textdomain( 'bpmt', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		include_once( dirname( 	__FILE__ ) . '/bpmt.php' );

	}
}
add_action( 'bp_loaded', 'bpmt_init' );
