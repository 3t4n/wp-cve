<?php

/**
 * Plugin Name:       Lazy Load Adsense
 * Plugin URI:        https://www.jorcus.com/
 * Description:       Place Google AdSense ads without affecting your page speed with lazy load technologies.
 * Version:           1.2.3
 * Author:            Jorcus
 * Author URI:        https://jorcus.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lazyload_adsense
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'LAZYLOAD_ADSENSE_VERSION', '1.2.3' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-lazyload_adsense.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_lazyload_adsense() {

	$plugin = new Lazyload_adsense();
	$plugin->run();

}
run_lazyload_adsense();