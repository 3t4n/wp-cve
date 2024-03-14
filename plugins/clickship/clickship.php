<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.clickship.com/
 * @since             1.0.1
 * @package           Clickship
 *
 * @wordpress-plugin
 * Plugin Name:       ClickShip
 * Plugin URI:        https://www.clickship.com/
 * Description:       We provide shipping rates at checkout to your customers.
 * Version:           1.0.1
 * Author:            ClickShip
 * Author URI:        https://www.clickship.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       clickship
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('CLICKSHIP_VERSION', '1.0.1');
define('CLICKSHIP_MIN_PHP_VERSION', '7.2');
define('CLICKSHIP_MIN_WP_VERSION', '5.8');
define( 'CLICKSHIP_URL', 'app.clickship.com/realtimerates/woocommerce/');
/**
 * Checks the system requirements 
 *
 * @return bool True if system requirements are met, false if not
 */
if( !function_exists('clickship_requirements_check') ){
	function clickship_requirements_check() {				
		if ( version_compare( PHP_VERSION, CLICKSHIP_MIN_PHP_VERSION, '<' ) ) {
			return false;
		}	
		if ( version_compare( get_bloginfo('version'), CLICKSHIP_MIN_WP_VERSION, '<' ) ) {
			return false;
		}	
		return true;
	}
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-clickship-activator.php
 */
function activate_clickship() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-clickship-activator.php';
	Clickship_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-clickship-deactivator.php
 */
function deactivate_clickship() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-clickship-deactivator.php';
	Clickship_Deactivator::deactivate();
}

/**
 * Notice about WordPress and PHP minimum requirements.
 *
 * @since 1.0.0
 *
 */
function clickship_requirements_error() {
	require_once plugin_dir_path( __FILE__ ) . 'admin/clickship-requirements-error.php';
}
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-clickship.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_clickship() {

	$plugin = new Clickship();
	$plugin->run();

}
/*
/*
* Check requirement
* @since 1.0.0
*/
if (clickship_requirements_check()) {	
	register_activation_hook( __FILE__, 'activate_clickship');
	register_deactivation_hook( __FILE__, 'deactivate_clickship');    
	run_clickship();
}else{
	add_action('admin_notices', 'clickship_requirements_error');
}?>