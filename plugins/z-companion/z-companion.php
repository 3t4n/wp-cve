<?php
/**
 * Plugin Name: Z Companion
 * Plugin URI: https://wpzita.com/z-companion
 * Description: Z-Companion plugin is specially made for wpzita themes.This plugin boost up features in the theme.
 * Version: 1.0.13
 * Author: WPZita
 * Author URI: https://wpzita.com
 * Text Domain: z-companion
 *
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
// Version constant for easy CSS refreshes
define('Z_COMPANION', '1.0.11');
define('Z_COMPANION_EXT_FILE', __FILE__ );
define('Z_COMPANION_PLUGIN_DIR_URL', plugin_dir_url(Z_COMPANION_EXT_FILE));
define('Z_COMPANION_BASENAME', plugin_basename(Z_COMPANION_EXT_FILE) );
define('Z_COMPANION_DIR_PATH', plugin_dir_path(Z_COMPANION_EXT_FILE ) );
function z_companion_text_domain(){
	$theme = wp_get_theme();
	$themeArr=array();
	$themeArr[] = $theme->get( 'TextDomain' );
	$themeArr[] = $theme->get( 'Template' );
	return $themeArr;
}

require_once( Z_COMPANION_DIR_PATH. '/import/zita-import.php' );
include_once(plugin_dir_path(__FILE__) . 'notify/notify.php' );


function z_companion_load_plugin(){
$theme = z_companion_text_domain(); 
	if(in_array("royal-shop", $theme)){
     require_once Z_COMPANION_DIR_PATH . 'royal-shop/royal-shop-admin/init.php';
      add_action( 'wp_enqueue_scripts', 'z_companion_royal_shop_scripts' );
	}
	
}
add_action('after_setup_theme', 'z_companion_load_plugin');

require_once Z_COMPANION_DIR_PATH . 'admin/enqueue.php';