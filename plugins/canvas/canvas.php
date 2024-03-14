<?php
/**
 * Plugin Name:       Canvas
 * Plugin URI:        https://codesupply.co/cnvs/
 * Description:       A revolutionary block-based page builder used for building layouts, an interplay of the WordPress block editor features and exceptional UI design.
 * Version:           2.4.4.1
 * Author:            Code Supply Co.
 * Author URI:        https://codesupply.co/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       canvas
 * Domain Path:       /languages
 *
 * @link              https://codesupply.co
 * @package           Canvas
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Variables
 */
define( 'CNVS_URL', plugin_dir_url( __FILE__ ) );
define( 'CNVS_PATH', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-canvas-activator.php
 */
function cnvs_activate() {
	do_action( 'cnvs_plugin_activation' );
}
register_activation_hook( __FILE__, 'cnvs_activate' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-canvas-deactivator.php
 */
function cnvs_deactivate() {
	do_action( 'cnvs_plugin_deactivation' );
}
register_deactivation_hook( __FILE__, 'cnvs_deactivate' );

/**
 * Language
 */
load_plugin_textdomain( 'canvas', false, plugin_basename( CNVS_PATH ) . '/languages' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . '/core/class-canvas.php';
