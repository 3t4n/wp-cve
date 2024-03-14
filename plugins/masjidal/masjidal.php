<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           karge-catalogue
 *
 * @wordpress-plugin
 * Plugin Name:       Muslim Prayer Time-Salah/Iqamah
 * Description:       Display the prayer(Athan) and/or Iqamah time for you masjid or location. Use as a widget or use the short codes and format it as you like. Even let's you display the monthly timetable.
 * Version:           1.8.7
 * Author:            Masjidal 
 * Author URI:        http://www.masjidal.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       masjidal
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}



/* Plugin Name */
$cwebPluginName="masjidal";

/* Use Domain as the folder name */
$PluginTextDomain="masjidal";


/**
 * The code that runs during plugin activation.
*/
 if (!function_exists('mptsi_activate_ado_plugin')) {
function mptsi_activate_ado_plugin() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/classes/activate-class.php';
    masjidal_namespace\MPSTI_Plugin_Activator::activate();
}
 }
/**
 * The code that runs during plugin deactivation.
*/
 if (!function_exists('mptsi_deactivate_ado_plugin')) {
function mptsi_deactivate_ado_plugin() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/classes/deactive-class.php';
    masjidal_namespace\MPSTI_Plugin_Deactivator::deactivate();
}
 }

/* Register Hooks For Start And Deactivate */
register_activation_hook( __FILE__, 'mptsi_activate_ado_plugin' );
register_deactivation_hook( __FILE__, 'mptsi_deactivate_ado_plugin' );

/**
 * The core plugin class that is used to define internationalization,
*/
require plugin_dir_path( __FILE__ ) . 'includes/classes/classCweb.php';

/*Include the Files in which we define the sortcodes for front End */
require plugin_dir_path( __FILE__ ) . 'public/short-codes.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
 if (!function_exists('mptsi_run_plugin_name_masjidal')) {
function mptsi_run_plugin_name_masjidal() {
    $plugin = new MPSTI_cWebClassado();
    $plugin->run();
}
 }
mptsi_run_plugin_name_masjidal();

/* Constant */
define('CWEB_MASJIDAL_PATH', plugin_dir_path(__FILE__) ); 
define('CWEB_MASJIDAL_URL', plugin_dir_url(__FILE__) ); 

/*
 * Include Custom Feild Files
 */

//Declares Common Function File 



require plugin_dir_path( __FILE__ ) . 'includes/function/functions.php';

