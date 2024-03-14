<?php

/**
 * The plugin bootstrap file
 *
 * @wordpress-plugin
 * Plugin Name: VGW Metis
 * Description: WordPress-Plugin zur Verwaltung von Text-Zählmarken für das METIS Verfahren der
 * Verwertungsgesellschaft WORT.
 * Version: 1.1.1
 * Requires at least: 5.6
 * Requires PHP: 8.0
 * Author: Verwertungsgesellschaft WORT
 * Author URI: https://www.vgwort.de
 * License: GPL-3.0+ License
 * URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: vgw-metis
 * Domain Path: /languages
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft WORT
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// add composer autoload
require __DIR__ . '/vendor/autoload.php';

// check php version --> load the bootstrap class or shut down
if ( version_compare( PHP_VERSION, '8.0', '<' ) ) {
	add_action( 'shutdown', 'vgw_metis_fail_php_version' );
} else {
	require __DIR__ . '/classes/plugin.php';

	$GLOBALS['wp_vgwort_metis'] = new WP_VGWORT\Plugin;
}

/**
 * show error notice if php version requirements not met
 *
 * @return void
 */
function vgw_metis_fail_php_version(): void {
	load_plugin_textdomain( 'vgw-metis', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	$message      = esc_html__( 'VGW-METIS requires PHP version 8.0+, plugin is currently NOT ACTIVE.', 'vgw-metis' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

/**
 * Helper for external plugins which wish to use Stream.
 *
 * @return WP_VGWORT\Plugin
 */
function vgw_metis_get_instance(): WP_VGWORT\Plugin {
	return $GLOBALS['wp_vgwort_metis'];
}
