<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.audioeye.com
 * @since             1.0.47
 * @package           AudioEye
 *
 * @wordpress-plugin
 * Plugin Name:       Accessibility by Audioeye
 * Description:       AudioEye automatically finds and fixes common accessibility issues on your site. This plugin provides an easy way to install AudioEye’s accessibility solution on WordPress.
 * Version:           1.0.47
 * Author:            AudioEye
 * Author URI:        www.audioeye.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       audioeye
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'AUDIOEYE_VERSION', '1.0.47' );

function activate_audioeye() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-audioeye-activator.php';
	Audioeye_Activator::activate();
}

function deactivate_audioeye() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-audioeye-deactivator.php';
	Audioeye_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_audioeye' );
register_deactivation_hook( __FILE__, 'deactivate_audioeye' );

require plugin_dir_path( __FILE__ ) . 'includes/class-audioeye.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_audioeye() {

	$plugin = new Audioeye();
	$plugin->run();

}
run_audioeye();
