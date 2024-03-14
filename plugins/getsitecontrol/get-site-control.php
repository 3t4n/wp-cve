<?php
/**
 * Plugin Name: Getsitecontrol popups
 * Plugin URI: https://getsitecontrol.com/
 * Description: Getsitecontrol is an ultimate popup plugin. Collect emails, conduct surveys, create exit popups, or promote sales and discounts with attention-grabbing popups. Place popups and grow sales.
 * Version: 3.0.0
 * Requires at least: 3.0.1
 * Tested up to: 5.7
 * Author: getsitecontrol
 * Author URI:  https://getsitecontrol.com/
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'GSC_URL' ) ) {
	define( 'GSC_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'GSC_PATH' ) ) {
	define( 'GSC_PATH', plugin_dir_path( __FILE__ ) );
}

require_once GSC_PATH . 'includes/get-site-control-wordpress.php';

GetsitecontrolWordPress::init();
