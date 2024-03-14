<?php
/**
 * Plugin Name:       Absolute Reviews
 * Description:       Add beautiful responsive and modern review boxes with valid JSON-LD schema to your posts with the "Advanced Reviews" plugin.
 * Version:           1.1.2
 * Author:            Code Supply Co.
 * Author URI:        https://codesupply.co
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       absolute-reviews
 * Domain Path:       /languages
 *
 * @link              https://codesupply.co
 * @package           ABR
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Variables
 */
define( 'ABR_URL', plugin_dir_url( __FILE__ ) );
define( 'ABR_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Plugin Activation.
 */
function abr_activation() {
	do_action( 'abr_activation' );
}
register_activation_hook( __FILE__, 'abr_activation' );

/**
 * Plugin Deactivation.
 */
function abr_deactivation() {
	do_action( 'abr_deactivation' );
}
register_deactivation_hook( __FILE__, 'abr_deactivation' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-absolute-reviews.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function abr_init() {

	$plugin = new ABR();
	$plugin->run();

}
abr_init();
