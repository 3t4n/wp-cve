<?php
/**
 * Simple SEO Improvements
 *
 * @package           simple-seo-improvements
 * @author            Marcin Pietrzak
 * @copyright         2021-2024 Marcin Pietrzak
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Simple SEO Improvements
 * Plugin URI:        http://iworks.pl/en/plugins/simple-seo-improvements/
 * Description:       Simple SEO improvements - lightweight solution to power up your website.
 * Version:           2.0.9
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            Marcin Pietrzak
 * Author URI:        http://iworks.pl/
 * Text Domain:       simple-seo-improvements
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * static options
 */
$base     = dirname( __FILE__ );
$includes = $base . '/includes';


/**
 * get plugin settings
 *
 * @since 1.0.6
 */
include_once $base . '/etc/options.php';

/**
 * @since 1.0.6
 */
if ( ! class_exists( 'iworks_options' ) ) {
	include_once $includes . '/iworks/options/options.php';
}

/**
 * require: Iworkssimple-seo-improvements Class
 */
if ( ! class_exists( 'iworks_simple_seo_improvements' ) ) {
	require_once $includes . '/iworks/class-simple-seo-improvements.php';
}
new iworks_simple_seo_improvements();

/**
 * i18n
 */
load_plugin_textdomain( 'simple-seo-improvements', false, plugin_basename( $base ) . '/languages' );


/**
 * install & uninstall plugin
 */
register_activation_hook( __FILE__, 'iworks_iworks_simple_seo_improvements_activate' );
register_deactivation_hook( __FILE__, 'iworks_iworks_simple_seo_improvements_deactivate' );

/**
 * load options
 *
 * since 2.6.8
 *
 */
global $iworks_simple_seo_improvements_options;
$iworks_simple_seo_improvements_options = null;

function get_iworks_simple_seo_improvements_options() {
	global $iworks_simple_seo_improvements_options;
	if ( is_object( $iworks_simple_seo_improvements_options ) ) {
		return $iworks_simple_seo_improvements_options;
	}
	$iworks_simple_seo_improvements_options = new iworks_options();
	$iworks_simple_seo_improvements_options->set_option_function_name( 'iworks_simple_seo_improvements_options' );
	$iworks_simple_seo_improvements_options->set_option_prefix( 'iworks_ssi_' );
	if ( method_exists( $iworks_simple_seo_improvements_options, 'set_plugin' ) ) {
		$iworks_simple_seo_improvements_options->set_plugin( basename( __FILE__ ) );
	}
	$iworks_simple_seo_improvements_options->init();
	$iworks_simple_seo_improvements_options = $iworks_simple_seo_improvements_options;
	return $iworks_simple_seo_improvements_options;
}

/**
 * Ask for vote
 */
include_once $includes . '/iworks/rate/rate.php';
do_action(
	'iworks-register-plugin',
	plugin_basename( __FILE__ ),
	__( 'Simple SEO Improvements', 'simple-seo-improvements' ),
	'simple-seo-improvements'
);

/**
 * Activate plugin function
 *
 * @since 2.6.0
 *
 */
function iworks_iworks_simple_seo_improvements_activate() {
	$options = get_iworks_simple_seo_improvements_options();
	$options->activate();
}

/**
 * Deactivate plugin function
 *
 * @since 2.6.0
 *
 */
function iworks_iworks_simple_seo_improvements_deactivate() {
}

