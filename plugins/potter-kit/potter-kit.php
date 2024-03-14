<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}
/**
 *
  * Plugin Name: Potter Kit - Elementor, Beaver Builder, Gutenberg Templates
  * Plugin URI: https://wppotter.com/potter-kit/
  * Description: Import Potter themes demo content, widgets and theme settings with just one click.
  * Version: 1.0.9
  * Author: wppotter
  * Author URI: https://wppotter.com
  * License: GPLv3 or later
  * Text Domain: potter-kit
  * Domain Path: /languages/
  *
 */

/*Define Constants for this plugin*/
define( 'POTTER_KIT_VERSION', '1.0.9' );
define( 'POTTER_KIT_PLUGIN_NAME', 'potter-kit' );
define( 'POTTER_KIT_PATH', plugin_dir_path( __FILE__ ) );
define( 'POTTER_KIT_URL', plugin_dir_url( __FILE__ ) );
define( 'POTTER_KIT_SCRIPT_PREFIX', ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '.min' : '.min' );

$upload_dir                   = wp_upload_dir();
$potter_kit_temp         = $upload_dir['basedir'] . '/potter-kit-temp/';
$potter_kit_temp_zip     = $upload_dir['basedir'] . '/potter-kit-temp-zip/';
$potter_kit_temp_uploads = $potter_kit_temp . '/uploads/';

define( 'POTTER_KIT_TEMP', $potter_kit_temp );
define( 'POTTER_KIT_TEMP_ZIP', $potter_kit_temp_zip );
define( 'POTTER_KIT_TEMP_UPLOADS', $potter_kit_temp_uploads );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-potter-kit-activator.php
 */
function activate_potter_kit() {
	require_once POTTER_KIT_PATH . 'includes/class-potter-kit-activator.php';
	Potter_Kit_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-potter-kit-deactivator.php
 */
function deactivate_potter_kit() {
	require_once POTTER_KIT_PATH . 'includes/class-potter-kit-deactivator.php';
	Potter_Kit_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_potter_kit' );
register_deactivation_hook( __FILE__, 'deactivate_potter_kit' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require POTTER_KIT_PATH . 'includes/class-potter-kit.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function potter_kit() {
	return Potter_Kit::instance();
}
potter_kit();
