<?php
/*
Plugin Name: Ultimate Client Dash
Plugin URI: https://ultimateclientdash.com/
Description: The ultimate tool for agencies & freelancers. Rebrand the Wordpress dashboard & login, leave personal notices for clients, create custom widgets, landing page mode and more.
Version: 4.6
Author: WP Codeus
Author URI: https://wpcodeus.com/
License: GPL2
Text Domain: ultimate-client-dash
*/


// If this file is called directly, abort
if ( ! defined( 'ABSPATH' ) ) {
     die ('Silly human what are you doing here');
}


// The core plugin file that is used to define internationalization, hooks and functions
require( plugin_dir_path( __FILE__ ) . '/include/plugin-functions.php');


// Function file that manages stylsheets
require( plugin_dir_path( __FILE__ ) . '/styling/styling-functions.php');


// Function file that manages admin views and functionality
require( plugin_dir_path( __FILE__ ) . '/admin/admin-functions.php');


// Get current plugin version
$ucd_plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
$ucd_plugin_version = $ucd_plugin_data['Version'];
global $ucd_plugin_version;


// Display additional links on UCD plugin action links
function ucd_plugin_add_settings_link( $links ) {
	$links = array_merge( array(
		'<a href="' . esc_url( admin_url( '/admin.php?page=ultimate-client-dash' ) ) . '">' . __( 'Settings', 'ultimate-client-dash' ) . '</a>',
    '<a href="https://ultimateclientdash.com/docs/" target="_blank" >' . __( 'Documentation', 'ultimate-client-dash' ) . '</a>'
	), $links );
	return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ucd_plugin_add_settings_link' );


// Update UCD options during plugin activation
function ucd_activation_actions(){
    do_action( 'ucd_extension_activation' );
}
register_activation_hook( __FILE__, 'ucd_activation_actions' );
// Set default values here
function ucd_default_options(){
    ucd_settings_activation_defaults();
}
add_action( 'ucd_extension_activation', 'ucd_default_options' );
add_action('upgrader_process_complete', 'ucd_default_options', 10, 2);
