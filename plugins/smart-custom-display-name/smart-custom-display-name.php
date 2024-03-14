<?php

/**
 * The Smart Custom Display Name Plugin
 *
 * Smart Custom Display Name allows you to set your Display Name to anything you like
 *
 * @wordpress-plugin
 * Plugin Name: Smart Custom Display Name
 * Plugin URI: https://wordpress.org/plugins/smart-custom-display-name/
 * Description: Set users "Display Name" to any custom value
 * Version: 5.0.1
 * Author: Peter Raschendorfer
 * Author URI: https://profiles.wordpress.org/petersplugins/
 * Text Domain: smart-custom-display-name
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */


// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Loader
 */
require_once( plugin_dir_path( __FILE__ ) . '/loader.php' );

?>