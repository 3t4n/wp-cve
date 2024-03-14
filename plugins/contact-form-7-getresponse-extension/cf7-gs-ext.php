<?php
/**
 *
 * @link              http://wensolutions.com/
 * @since             1.0.0
 * @package           Cf7_Gr_Ext
 *
 * @wordpress-plugin
 * Plugin Name:       Contact Form 7 GetResponse Extension
 * Plugin URI:        http://wensolutions.com/plugins/contact-form-7-getresponse-extension
 * Description:       A very easy plugin to integrate GetResponse campaigns with Contact Form 7.
 * Version:           1.0.8
 * Requires at least: 3.9
 * Requires PHP:      5.6
 * Tested up to:      6.4
 * Author:            WEN Solutions
 * Author URI:        http://wensolutions.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cf7-gr-ext
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'CF7_GR_EXT_BASE_FILE', __FILE__ );
define( 'CF7_GR_EXT_BASE', plugin_dir_path( CF7_GR_EXT_BASE_FILE ) );
define( 'CF7_GR_EXT_BASE_NAME', plugin_basename( __FILE__ ) );
define( 'CF7_GR_EXT_BASE_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );

// Load engine.
require 'cf7-gr-ext.php';
