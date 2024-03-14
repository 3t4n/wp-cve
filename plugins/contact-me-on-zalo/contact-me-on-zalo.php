<?php
/**
 * Plugin Name: Contact Me on Zalo
 * Plugin URI: https://pixelplus.vn
 * Description: Contact Me on Zalo.
 * Version: 1.0.4
 * Author: Nam Truong
 * Author URI: https://pixelplus.vn
 *
 * Text Domain: contact-me-on-zalo
 * Domain Path: /languages/
 *
 * @package Contact_Me_On_Zalo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define.
define( 'CMOZ_VERSION', '1.0.4' );
define( 'CMOZ_FILE', __FILE__ );
define( 'CMOZ_NAME', basename( CMOZ_FILE ) );
define( 'CMOZ_BASE_NAME', plugin_basename( CMOZ_FILE ) );
define( 'CMOZ_PATH', plugin_dir_path( CMOZ_FILE ) );
define( 'CMOZ_URL', plugin_dir_url( CMOZ_FILE ) );
define( 'CMOZ_MODULES_PATH', CMOZ_PATH . 'modules/' );
define( 'CMOZ_ASSETS_URL', CMOZ_URL . 'assets/' );

require_once CMOZ_PATH . '/includes/class-contact-me-on-zalo.php';

/**
 * [cmoz_load_plugin_textdomain description]
 * @return [type] [description]
 */
function cmoz_load_plugin_textdomain() {
	load_plugin_textdomain( 'contact-me-on-zalo', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'cmoz_load_plugin_textdomain' );

CMOZ::instance();
