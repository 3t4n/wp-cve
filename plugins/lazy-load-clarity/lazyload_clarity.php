<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Lazy Load Clarity
 * Plugin URI:        https://www.jorcus.com/
 * Description:       Place Microsoft Clarity without affecting your page speed with lazy load technologies.
 * Version:           1.1.1
 * Author:            Jorcus
 * Author URI:        https://jorcus.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lazyload_clarity
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'LAZYLOAD_CLARITY_VERSION', '1.1.1' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-lazyload_clarity.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_lazyload_clarity() {

	$plugin = new Lazyload_clarity();
	$plugin->run();

}
run_lazyload_clarity();