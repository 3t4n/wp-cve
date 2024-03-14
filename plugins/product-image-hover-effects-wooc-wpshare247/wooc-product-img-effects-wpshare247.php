<?php
/**
 * Plugin Name: Product Image Hover Effects WOOC - WPSHARE247
 * Plugin URI: https://wpshare247.com/
 * Description: Add effect for loop woocommerce product image when hover
 * Version: 1.0.7
 * Author: Wpshare247.com
 * Author URI: https://wpshare247.com
 * Text Domain: ws247-piew
 * Domain Path: /languages/
 * Requires at least: 4.9
 * Requires PHP: 5.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WS247_PIEW', __FILE__ );
define( 'WS247_PIEW_PLUGIN_DIR', untrailingslashit( dirname( WS247_PIEW ) ) );
define( 'WS247_PIEW_PLUGIN_INC_DIR', WS247_PIEW_PLUGIN_DIR . '/inc' );  
require_once WS247_PIEW_PLUGIN_INC_DIR . '/define.php';
require_once WS247_PIEW_PLUGIN_INC_DIR . '/class.setting.page.php';
require_once WS247_PIEW_PLUGIN_INC_DIR . '/theme_functions.php';

