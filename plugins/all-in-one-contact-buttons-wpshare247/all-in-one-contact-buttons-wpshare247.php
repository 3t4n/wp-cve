<?php
/**
 * Plugin Name: All-in-one contact buttons - WPSHARE247
 * Plugin URI: https://wpshare247.com/
 * Description: Floating click to contact buttons All-In-One
 * Version: 1.0.5
 * Author: Wpshare247.com
 * Author URI: https://wpshare247.com
 * Text Domain: ws247-aio-ct-button
 * Domain Path: /languages/
 * Requires at least: 4.9
 * Requires PHP: 5.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WS247_AIO_CT_BUTTON', __FILE__ );
define( 'WS247_AIO_CT_BUTTON_PLUGIN_DIR', untrailingslashit( dirname( WS247_AIO_CT_BUTTON ) ) );
define( 'WS247_AIO_CT_BUTTON_PLUGIN_INC_DIR', WS247_AIO_CT_BUTTON_PLUGIN_DIR . '/inc' );  
require_once WS247_AIO_CT_BUTTON_PLUGIN_INC_DIR . '/define.php';
//require_once WS247_AIO_CT_BUTTON_PLUGIN_INC_DIR . '/class.helper.php';
require_once WS247_AIO_CT_BUTTON_PLUGIN_INC_DIR . '/class.setting.page.php';
require_once WS247_AIO_CT_BUTTON_PLUGIN_INC_DIR . '/theme_functions.php';

