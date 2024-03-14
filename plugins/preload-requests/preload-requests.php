<?php
/*
 Plugin Name: Preload Requests 
 Description: Plugin Provide you to Add preload links to your website easily, compatible with all major browsers. It will improve your site SEO score.
 Author: Geek Code Lab
 Version: 1.6
 Author URI: https://geekcodelab.com/
 Text Domain: preload-requests
*/

if( !defined( 'ABSPATH' ) ) exit;
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

define("GCLPR_BUILD",1.6);

if(!defined("GCLPR_PLUGIN_DIR_PATH"))
	define("GCLPR_PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));	
if(!defined("GCLPR_PLUGIN_URL"))
	define("GCLPR_PLUGIN_URL", plugins_url().'/'.basename(dirname(__FILE__)));

require_once( GCLPR_PLUGIN_DIR_PATH .'functions.php');
require_once( GCLPR_PLUGIN_DIR_PATH .'admin/metabox.php');
require_once( GCLPR_PLUGIN_DIR_PATH .'admin/settings.php');

$plugin = plugin_basename(__FILE__);
add_filter( "plugin_action_links_$plugin", 'gclpr_add_plugin_settings_link');
function gclpr_add_plugin_settings_link( $links ) {
	$support_link = '<a href="https://geekcodelab.com/contact/"  target="_blank" >' . __( 'Support', 'preload-requests' ) . '</a>'; 
	array_unshift( $links, $support_link );

	$settings_link = '<a href="'. admin_url() .'admin.php?page=preload-requests">' . __( 'Settings', 'preload-requests' ) . '</a>';
	array_unshift( $links, $settings_link );

	return $links;
}