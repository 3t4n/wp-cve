<?php
/*
Plugin Name:       Everest Toolkit
Plugin URI:        https://wordpress.org/plugins/everest-toolkit/
Description:       Toolkit for themes and contains demo import options required for everestthemes.
Version:           1.2.3
Author:            everestthemes
Author URI:        https://everestthemes.com
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain:       everest-toolkit
Domain Path:       /languages
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

define( 'EVERESTTOOLKIT_VERSION', '1.2.3' );

// Define EVERESTTOOLKIT_PLUGIN_FILE.
if ( ! defined( 'EVERESTTOOLKIT_PLUGIN_FILE' ) ) {

	define( 'EVERESTTOOLKIT_PLUGIN_FILE', __FILE__ );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-everest-toolkit-activator.php
 */
function activate_everest_toolkit() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-everest-toolkit-activator.php';
	Everest_Toolkit_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-everest-toolkit-deactivator.php
 */
function deactivate_everest_toolkit() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-everest-toolkit-deactivator.php';
	Everest_Toolkit_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_everest_toolkit' );
register_deactivation_hook( __FILE__, 'deactivate_everest_toolkit' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-everest-toolkit.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_everest_toolkit() {

	$plugin = new Everest_Toolkit();
	$plugin->run();
}
run_everest_toolkit();


// Include the main Demo Importer class.
if ( ! class_exists( 'Everestthemes_Demo_Importer' ) ) {
	include_once dirname( __FILE__ ) . '/includes/demo-importer/class-everestthemes-demo-importer.php';
}

/**
 * Main instance of Everestthemes_Demo_Importer.
 *
 * Returns the main instance of TET to prevent the need to use globals.
 *
 * @since 1.0.0
 * @return Everestthemes_Demo_Importer
 */
function ET() {

	return Everestthemes_Demo_Importer::instance();
}

$theme = everest_toolkit_theme();

$theme_array = everest_toolkit_theme_array();

if ( $theme->get( 'Author' ) === 'everestthemes' && in_array( $theme->get( 'TextDomain' ), $theme_array ) ) {

	// Global for backwards compatibility.
	$GLOBALS['everest-demo-importer'] = ET();
}
