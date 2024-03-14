<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              nakunakifi.com
 * @since             4.0.0
 * @package           Google_Photos_Albums_Gallery
 *
 * @wordpress-plugin
 * Plugin Name:       Google Photos Albums Gallery
 * Plugin URI:        cheshirewebsolutions.com
 * Description:       Google Photos Gallery. This is a complete rewrite for Google Photo Library API.
 * Version:           4.0.3
 * Author:            Ian Kennerley
 * Author URI:        nakunakifi.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       google-photos-albums-gallery
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'GOOGLE_PHOTOS_ALBUMS_GALLERY_VERSION', '4.0.3' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-google-photos-albums-gallery-activator.php
 */
function activate_google_photos_albums_gallery() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-google-photos-albums-gallery-activator.php';
	Google_Photos_Albums_Gallery_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-google-photos-albums-gallery-deactivator.php
 */
function deactivate_google_photos_albums_gallery() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-google-photos-albums-gallery-deactivator.php';
	Google_Photos_Albums_Gallery_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_google_photos_albums_gallery' );
register_deactivation_hook( __FILE__, 'deactivate_google_photos_albums_gallery' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-google-photos-albums-gallery.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    4.0.0
 */
function run_google_photos_albums_gallery() {

	$plugin = new Google_Photos_Albums_Gallery();
	$plugin->run();

}
run_google_photos_albums_gallery();
