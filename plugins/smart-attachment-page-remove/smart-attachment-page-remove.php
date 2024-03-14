<?php

/**
 * The Smart Attachment Page Remove Plugin
 *
 * Smart Attachment Page Remove allows you to completely remove Attachment Pages from your Blog
 *
 * @wordpress-plugin
 * Plugin Name: Smart Attachment Page Remove
 * Plugin URI: https://wordpress.org/plugins/smart-attachment-page-remove/
 * Description: Completely remove Attachment Pages from your Blog
 * Version: 4.0.3
 * Author: Peter Raschendorfer
 * Author URI: https://profiles.wordpress.org/petersplugins/
 * Text Domain: smart-attachment-page-remove
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

 
// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Loader
 * @since 3
 */
require_once( plugin_dir_path( __FILE__ ) . '/loader.php' );

?>