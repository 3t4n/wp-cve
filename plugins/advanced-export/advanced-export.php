<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}
/**
 *
 * @link              https://addonspress.com/
 * @since             1.0.0
 * @package           Advanced_Export
 *
 * @wordpress-plugin
 * Plugin Name:       Advanced Export
 * Plugin URI:        https://addonspress.com/item/advanced-export
 * Description:       Advanced Export with Options to Export Widget, Customizer and Media Files
 * Version:           1.0.7
 * Author:            AddonsPress
 * Author URI:        https://addonspress.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       advanced-export
 * Domain Path:       /languages
 */

/*Define Constants for this plugin*/
define( 'ADVANCED_EXPORT_VERSION', '1.0.7' );
define( 'ADVANCED_EXPORT_PATH', plugin_dir_path( __FILE__ ) );
define( 'ADVANCED_EXPORT_URL', plugin_dir_url( __FILE__ ) );

$upload_dir                   = wp_upload_dir();
$advanced_export_temp         = $upload_dir['basedir'] . '/advanced-export-temp/';
$advanced_export_temp_uploads = $advanced_export_temp . '/uploads/';

define( 'ADVANCED_EXPORT_TEMP', $advanced_export_temp );
define( 'ADVANCED_EXPORT_TEMP_UPLOADS', $advanced_export_temp_uploads );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-advanced-export-activator.php
 */
function activate_advanced_export() {
	require_once ADVANCED_EXPORT_PATH . 'includes/class-advanced-export-activator.php';
	Advanced_Export_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-advanced-export-deactivator.php
 */
function deactivate_advanced_export() {
	require_once ADVANCED_EXPORT_PATH . 'includes/class-advanced-export-deactivator.php';
	Advanced_Export_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_advanced_export' );
register_deactivation_hook( __FILE__, 'deactivate_advanced_export' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require ADVANCED_EXPORT_PATH . 'includes/class-advanced-export.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function advanced_export() {
	return Advanced_Export::instance();
}
advanced_export();
