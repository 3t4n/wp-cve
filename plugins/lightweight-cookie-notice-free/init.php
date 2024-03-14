<?php

/*
Plugin Name: Lightweight Cookie Notice
Description: Generates a lightweight cookie notice. (Lite Version)
Version: 1.09
Author: DAEXT
Author URI: https://daext.com
Text Domain: lightweight-cookie-notice-free
*/

//Prevent direct access to this file
if ( ! defined( 'WPINC' ) ) {
	die();
}

//Class shared across public and admin
require_once( plugin_dir_path( __FILE__ ) . 'shared/class-daextlwcnf-shared.php' );
require_once( plugin_dir_path( __FILE__ ) . '/vendor/autoload.php' );

//Public
require_once( plugin_dir_path( __FILE__ ) . 'public/class-daextlwcnf-public.php' );
add_action( 'plugins_loaded', array( 'Daextlwcnf_Public', 'get_instance' ) );

//Admin
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	//Admin
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-daextlwcnf-admin.php' );
	add_action( 'plugins_loaded', array( 'Daextlwcnf_Admin', 'get_instance' ) );

	//Activate
	register_activation_hook( __FILE__, array( Daextlwcnf_Admin::get_instance(), 'ac_activate' ) );

	//Deactivate
	register_deactivation_hook( __FILE__, array( Daextlwcnf_Admin::get_instance(), 'dc_deactivate' ) );

}

//Ajax
if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

	//Admin
	require_once( plugin_dir_path( __FILE__ ) . 'class-daextlwcnf-ajax.php' );
	add_action( 'plugins_loaded', array( 'Daextlwcnf_Ajax', 'get_instance' ) );

}

//Customize the action links in the "Plugins" menu
function daextlwcnf_customize_action_links( $actions ) {
	$actions[] = '<a href="https://daext.com/lightweight-cookie-notice/">' . esc_html__('Buy the Pro Version', 'daextlwcnf') . '</a>';
	return $actions;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'daextlwcnf_customize_action_links' );