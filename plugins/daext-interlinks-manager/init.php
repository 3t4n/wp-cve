<?php
/*
Plugin Name: Interlinks Manager
Description: Manages the internal links of your WordPress website. (Lite Version)
Version: 1.07
Author: DAEXT
Author URI: https://daext.com
Text Domain: daext-interlinks-manager
*/

//Prevent direct access to this file
if ( ! defined( 'WPINC' ) ) {
	die();
}

//Class shared across public and admin
require_once( plugin_dir_path( __FILE__ ) . 'shared/class-daextinma-shared.php' );

//Admin
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	//Admin
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-daextinma-admin.php' );
	add_action( 'plugins_loaded', array( 'Daextinma_Admin', 'get_instance' ) );

	//Activate
	register_activation_hook( __FILE__, array( Daextinma_Admin::get_instance(), 'ac_activate' ) );

}

//Ajax
if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

	//Admin
	require_once( plugin_dir_path( __FILE__ ) . 'class-daextinma-ajax.php' );
	add_action( 'plugins_loaded', array( 'Daextinma_Ajax', 'get_instance' ) );

}

//Customize the action links in the "Plugins" menu
function daextinma_customize_action_links( $actions ) {
	$actions[] = '<a href="https://daext.com/interlinks-manager/">' . esc_html__( 'Buy the Pro Version', 'daext-interlinks-manager') . '</a>';
	return $actions;
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'daextinma_customize_action_links' );