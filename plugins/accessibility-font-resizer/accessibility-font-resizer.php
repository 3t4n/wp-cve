<?php
/**
 * Plugin Name: Accessibility Font Resizer
 * Plugin URI: https://www.satellitewp.com/accessibility-font-resizer
 * Description: Make accessibility better for your visitors by enabling them to resize the text on your website and make it bigger.
 * Author: SatelliteWP
 * Version: 1.0.4
 * Author URI: https://www.satellitewp.com
 * Text Domain: accessibility-font-resizer
 */

Namespace SatelliteWP;

defined( 'ABSPATH' ) or die( 'No direct access call...' );


// When plugin is installed
function activate_plugin() {
	\SatelliteWP\Includes\Classes\Font_Resizer::activate();
}

// When plugin is deactivated
function deactivate_plugin() {
	\SatelliteWP\Includes\Classes\Font_Resizer::deactivate();
}

// When plugin is uninstalled
function uninstall_plugin() {
	\SatelliteWP\Includes\Classes\Font_Resizer::uninstall();
}

register_activation_hook( __FILE__, __NAMESPACE__ . '\activate_plugin' );
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\deactivate_plugin' );
register_uninstall_hook( __FILE__, __NAMESPACE__ . '\uninstall_plugin' );

require_once( 'includes/classes/font-resizer.php' );

// Initialisation
$afr = new \SatelliteWP\Includes\Classes\Font_Resizer();
$afr->run();
