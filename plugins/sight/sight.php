<?php
/**
 * Plugin Name:       Sight – Professional Image Gallery and Portfolio
 * Description:       Beautiful responsive galleries and portfolio of your work.
 * Version:           1.1.1
 * Author:            Code Supply Co.
 * Author URI:        https://codesupply.co
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sight
 * Domain Path:       /languages
 *
 * @link              https://codesupply.co
 * @package           Sight
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Variables
 */
define( 'SIGHT_URL', plugin_dir_url( __FILE__ ) );
define( 'SIGHT_PATH', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sight-activator.php
 */
function sight_activate() {
	do_action( 'sight_plugin_activation' );
}
register_activation_hook( __FILE__, 'sight_activate' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sight-deactivator.php
 */
function sight_deactivate() {
	do_action( 'sight_plugin_deactivation' );
}
register_deactivation_hook( __FILE__, 'sight_deactivate' );

/**
 * Language
 */
load_plugin_textdomain( 'sight', false, plugin_basename( SIGHT_PATH ) . '/languages' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'core/class-sight.php';
