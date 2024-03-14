<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://dipankar-team.business.site
 * @since             1.0.0
 * @package           Customwprest
 *
 * @wordpress-plugin
 * Plugin Name:       Ultimate Endpoints With Rest Api
 * Plugin URI:        http://wcra.gmnckkp.in
 * Description:       Add Custom Endpoints to the Wordpress REST API? Fantastic! Let’s get started with this plugin
 * Version:           2.2.2
 * Author:            Dipankar Pal
 * Author URI:        https://profiles.wordpress.org/dipankarpal212
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       customwprest
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */

define( 'WCRA_PLUGIN_VERSION', '2.2.2' );
define( 'WCRA_DB', 'wcra_' );
define( 'WCRA_PLUGIN_SLUG', 'custom-wp-rest-api' );
define( 'WCRA_PLUGIN_TEXTDOMAIN', 'customwprest' );

$plugin = plugin_basename( __FILE__ );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-customwprest-activator.php
 */
function wcra_activate_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-customwprest-activator.php';
	WCRA_Activator::activate();
	
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-customwprest-deactivator.php
 */
function wcra_deactivate_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-customwprest-deactivator.php';
	WCRA_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'wcra_activate_plugin' );
register_deactivation_hook( __FILE__, 'wcra_deactivate_plugin' );
add_filter( "plugin_action_links_$plugin", 'wcra_add_settings_link' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-customwprest.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function wcra_run_customwprest() {

	$plugin = new WCRA_Controller();
	$plugin->run();

}
wcra_run_customwprest();




