<?php
/**
 * Plugin Name: Easy Amazon Product Information
 * Plugin URI: http://jensmueller.one/easy-amazon-product-information/
 * Description: Mit EAPI können Sie aus der Amazon API zahlreiche Produktinformationen auslesen und in Ihre Webseite einbinden. Die Anzeige auf der Webseite kann individuell nach Ihren Bedürfnissen angepasst werden.
 * Version: 4.0.1
 * Author: Jens Müller
 * Author URI: http://jensmueller.one
 * Text Domain: easy-amazon-product-information
 */
define('EAPI_VERSION', '4.0.1');
define('EAPI_PLUGIN_DIR',  plugin_dir_path( __FILE__ ));
define('BUILD_VERSION',  '8');

require_once(  EAPI_PLUGIN_DIR . 'eapi_handler.php' );
include_once(  EAPI_PLUGIN_DIR . 'eapi_amazon.php' );
include_once(  EAPI_PLUGIN_DIR . 'eapi_dashboard.php' );
include_once(  EAPI_PLUGIN_DIR . 'eapi_aws.php' );
//has the ebay API
if( file_exists( EAPI_PLUGIN_DIR . 'eapi_ebay.php') ) {
	include_once(  EAPI_PLUGIN_DIR . 'eapi_ebay.php' );
	define('HAS_EAPI_PLUS',  true);
}else{
	define('HAS_EAPI_PLUS',  false);
}

//replaces the tag
add_shortcode( 'eapi', 'eapi_replace_eapi_tag' );
//display backend
add_action( 'admin_menu', 'eapi_eapi_plugin_menu' );
//color-picker
add_action( 'admin_enqueue_scripts', 'eapi_mw_enqueue_color_picker' );
//public stylesheet will get loaded
add_action( 'wp_enqueue_scripts', 'eapi_load_css' );
//registers the activation of the plugin
register_activation_hook( __FILE__, 'eapi_install' );
//registers deactivation of the plugin
register_uninstall_hook( EAPI_PLUGIN_DIR . 'uninstall.php', '');
//loads the text-file
load_plugin_textdomain('easy-amazon-product-information', false, dirname(plugin_basename(__FILE__)) . '/languages/');
//allows the usage in the sidebar
add_filter('widget_text', 'do_shortcode');

?>
