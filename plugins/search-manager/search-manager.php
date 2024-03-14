<?php
/**
 * Plugin Name: Search Manager Lite for WooCommerce and WordPress
 * Plugin URI:  http://searchmanagerwp.com/
 * Description: Search Manager Lite — plugin for WordPress and WooCommerce, which allows you to enhance your site search with the ability to search by extra fields such as comments, excerpt, tags, SKU etc.
 * Version:     1.4
 * Author:      Custom4Web
 * Author URI:  https://www.custom4web.com/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wcstm_lite
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

define( 'WCSTM_LITE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WCSTM_LITE_BASENAME', plugin_basename( __FILE__ ) );
define( 'WCSTM_LITE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once WCSTM_LITE_PLUGIN_DIR . 'includes/class-wcstm-lite.php';
