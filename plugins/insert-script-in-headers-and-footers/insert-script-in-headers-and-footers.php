<?php
 
/*
Plugin Name: Insert Script In Headers And Footers
Description: A plugin to insert script in headers and footers
Author: Geek Code Lab
Version: 2.4
Author URI: https://geekcodelab.com/
Text Domain: insert-script-in-headers-and-footers
*/

if( !defined( 'ABSPATH' ) ) exit;

define("ISHF_BUILD", '2.4');

require_once( plugin_dir_path (__FILE__) .'functions.php' );

add_action('admin_menu', 'ishf_admin_menu_header_footer_script' );

add_action( 'admin_init', 'ishf_registerSettings' );

add_action( 'admin_enqueue_scripts', 'ishf_enqueue_styles_scripts_header_footer_script' );

add_action('wp_head', 'ishf_frontendHeaderScript',100);

add_action('wp_body_open', 'ishf_frontendBodyScript',100);

add_action('wp_footer', 'ishf_frontendFooterScript',100);

register_activation_hook( __FILE__, 'ishf_plugin_activation' );

function ishf_plugin_activation(){
	if (is_plugin_active( 'insert-script-in-headers-and-footers-pro/insert-script-in-headers-and-footers-pro.php' ) ) {
		deactivate_plugins('insert-script-in-headers-and-footers-pro/insert-script-in-headers-and-footers-pro.php');
	}
}

$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'ishf_plugin_add_settings_link');

function ishf_plugin_add_settings_link( $links ) { 
	$support_link = '<a href="https://geekcodelab.com/contact/"  target="_blank" >' . __( 'Support','insert-script-in-headers-and-footers' ) . '</a>'; 
	array_unshift( $links, $support_link );	

	$pro_link = '<a href="https://geekcodelab.com/wordpress-plugins/insert-script-in-headers-and-footers-pro/"  target="_blank" style="color:#46b450;font-weight: 600;">' . __( 'Premium Upgrade', 'insert-script-in-headers-and-footers' ) . '</a>'; 
	array_unshift( $links, $pro_link );

	$settings_link = '<a href="options-general.php?page=insert-script-in-header-and-footer-option">' . __( 'Settings','insert-script-in-headers-and-footers' ) . '</a>'; 	
	array_unshift( $links, $settings_link );	

	return $links;
}
function ishf_registerSettings() {
	$plugin_data = get_plugin_data( __FILE__ );
	$plugin_name = $plugin_data['Name'];
	register_setting( $plugin_name, 'insert_header_script_gk', 'trim' );
	register_setting( $plugin_name, 'insert_body_script_gk', 'trim' );
	register_setting( $plugin_name, 'insert_footer_script_gk', 'trim' );
}


function ishf_frontendHeaderScript(){
	ishf_output('insert_header_script_gk');
}

function ishf_frontendFooterScript(){	
	ishf_output('insert_footer_script_gk');	
}

function ishf_frontendBodyScript() {	
	ishf_output('insert_body_script_gk');
}

function ishf_admin_menu_header_footer_script(){
	add_options_page( 'Insert Script In Headers And Footers', 'Insert Script In Headers And Footers', 'manage_options', 'insert-script-in-header-and-footer-option', 'ishf_options_menu_header_footer_script');}

function ishf_options_menu_header_footer_script(){
	if(!current_user_can('manage_options') ) {
		wp_die( __('You do not have sufficient permissions to access this page.','insert-script-in-headers-and-footers') );
	}	
	include( plugin_dir_path( __FILE__ ) . 'options.php' );
}

function ishf_enqueue_styles_scripts_header_footer_script()
{
    if( is_admin() ) {
        $css= plugins_url() . '/'.  basename(dirname(__FILE__)) . "/assets/css/style.css";
        wp_enqueue_style( 'main-header-footer-script-css', $css, array(), ISHF_BUILD );
    }
}
?>