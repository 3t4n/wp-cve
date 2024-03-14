<?php
/**
* Plugin Name: WooCommerce Accordions
* Plugin URI: https://magnigenie.com/
* Description: This plugin allows you to convert your woocommerce tabs to accordion.
* Version: 1.2
* Author: Magnigenie
* Author URI: http://magnigenie.com
* Text Domain: wooatm
* Domain Path: languages
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// No direct file access
! defined( 'ABSPATH' ) AND exit;

define('WOOATM_FILE', __FILE__);
define('WOOATM_PATH', plugin_dir_path(__FILE__));
define('WOOATM_BASE', plugin_basename(__FILE__));

//Plugin Localization
add_action('plugins_loaded', 'wooatm_load_textdomain');

function wooatm_load_textdomain() {
	load_plugin_textdomain( 'wooatm', false, dirname( plugin_basename( __FILE__ ) ). '/languages/' );
}

if( !class_exists('Woo_Accordions') ) {
	require WOOATM_PATH . '/includes/class-wooatm.php';
}

new Woo_Accordions();