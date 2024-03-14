<?php
/**
 * Main file.
 *
 * @package   linkPizza-Manager
 * @author    Arjan Pronk <arjan.pronk@zeef.com>
 * @license   GPL-2.0+
 * @link      http://linkpizza.com
 *
 * @wordpress-plugin
 * Plugin Name:       linkPizza-Manager
 * Plugin URI:        http://linkpizza.com
 * Description:       Using LinkPizza all links on your website will be automatically monetized.
 * Version:           5.5.3
 * Author:            Arjan Pronk
 * Author URI:        arjan.pronk@linkpizza.com
 * Text Domain:       linkpizza-manager
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PZZ_VERSION', '5.5.3' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function linkpizza_manager_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-linkPizza-Manager-activator.php';
	linkPizza_Manager_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function linkpizza_manager_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-linkPizza-Manager-deactivator.php';
	linkPizza_Manager_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'linkpizza_manager_activate' );
register_deactivation_hook( __FILE__, 'linkpizza_manager_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-linkPizza-Manager.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function linkpizza_manager_run() {

	$plugin = new linkPizza_Manager();
	$plugin->run();

}
linkpizza_manager_run();

define( 'PZZ_BASE_FILE', plugin_basename( __FILE__ ) );
