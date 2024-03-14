<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://timersys.com/geotargeting/
 * @since             1.0.0
 * @package           GeoTarget
 *
 * @wordpress-plugin
 * Plugin Name:       GeoTargeting Lite
 * Plugin URI:        https://timersys.com/geotargeting/
 * Description:       Geo Targeting for WordPress will let you country-target your content based on users IP's and Geocountry Ip database
 * Version:           1.3.6.1
 * Author:            Timersys
 * Author URI:        https://timersys.com/geotargeting/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       geotarget
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'GEOT_VERSION' , '1.3.6.1');
define( 'GEOT_PLUGIN_FILE' , __FILE__);
define( 'GEOT_PLUGIN_DIR' , plugin_dir_path(__FILE__) );
define( 'GEOT_PLUGIN_URL' , plugin_dir_url(__FILE__) );
define( 'GEOT_PLUGIN_HOOK' , basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) );


/**
 * The code that runs during plugin activation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-geotarget-activator.php';
// if geot maxmind is not enabled add it
if( ! class_exists('GeotMaxmind') ) {
	require_once GEOT_PLUGIN_DIR . 'includes/class-geot-maxmind-activator.php';
	require_once GEOT_PLUGIN_DIR . 'includes/class-geot-maxmind-deactivator.php';
	require_once GEOT_PLUGIN_DIR . 'includes/class-geot-maxmind.php';
	$geotmax = new GeotMaxmind();
}
/**
 * The code that runs during plugin deactivation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-geotarget-deactivator.php';

/** This action is documented in includes/class-geotarget-activator.php */
register_activation_hook( __FILE__, array( 'GeoTarget_Activator', 'activate' ) );

/** This action is documented in includes/class-geotarget-deactivator.php */
register_deactivation_hook( __FILE__, array( 'GeoTarget_Deactivator', 'deactivate' ) );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-geotarget.php';

/**
 * Store the plugin global
 */
global $geot;

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
$geot = new GeoTarget();

$geot->run();

require_once plugin_dir_path( __FILE__ ) . 'includes/functions.php';
