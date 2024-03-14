<?php
/*
Plugin Name: Helpful
Description: Easily add a "Was it helpful?" survey on your blog and knowledge base pages. (Lite Version)
Version: 1.08
Author: DAEXT
Author URI: https://daext.com
Text Domain: daext-helpful
*/

//Prevent direct access to this file
if ( ! defined( 'WPINC' ) ) {
	die();
}

//Shared across public and admin
require_once( plugin_dir_path( __FILE__ ) . 'shared/class-daexthefu-shared.php' );

//Perform the Gutenberg related activities only if Gutenberg is present
if ( function_exists( 'register_block_type' ) ) {
	require_once( plugin_dir_path( __FILE__ ) . 'blocks/src/init.php' );
}

require_once( plugin_dir_path( __FILE__ ) . 'public/class-daexthefu-public.php' );
add_action( 'plugins_loaded', array( 'daexthefu_Public', 'get_instance' ) );

//Admin
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	//Admin
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-daexthefu-admin.php' );
	add_action( 'plugins_loaded', array( 'daexthefu_Admin', 'get_instance' ) );

	//Activate
	register_activation_hook( __FILE__, array( daexthefu_Admin::get_instance(), 'ac_activate' ) );

}

//Ajax
if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

	//Admin
	require_once( plugin_dir_path( __FILE__ ) . 'class-daexthefu-ajax.php' );
	add_action( 'plugins_loaded', array( 'daexthefu_Ajax', 'get_instance' ) );

}