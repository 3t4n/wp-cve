<?php
/**
 * Plugin Name: Prevent Files / Folders Access
 * Plugin URI: http://miniorange.com
 * Description: Allows to protect your files and folders (wp-content, uploads, images, pdf, documents) from public access, Role base folder access, User base folder access, giving access to only logged in users.
 * Version: 2.5.3
 * Author: miniOrange
 * Author URI: http://miniorange.com
 * License: GPL2
 *
 * @package    Media_Restriction
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.1.1 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MO_MEDIA_RESTRICTION_PLUGIN_NAME_VERSION', '2.5.3' );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-media-restriction-activator.php
 */
function activate_media_restriction() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-media-restriction-activator.php';
	Media_Restriction_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-media-restriction-deactivator.php
 */
function deactivate_media_restriction() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-media-restriction-deactivator.php';
	Media_Restriction_Deactivator::deactivate();
}

/**
 * Media restriction plugin feedback request;
 *
 * @return void
 */
function mo_media_restriction_feedback_request() {
	Mo_Media_Restriction_Admin_Feedback::mo_media_restriction_display_feedback_form();
}

register_activation_hook( __FILE__, 'activate_media_restriction' );
register_deactivation_hook( __FILE__, 'deactivate_media_restriction' );
add_action( 'admin_footer', 'mo_media_restriction_feedback_request' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-media-restriction.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.1.1
 */
function run_media_restriction() {

	$plugin = new Media_Restriction();
	$plugin->run();

}
run_media_restriction();
