<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.catchplugins.com
 * @since             1.0.0
 * @package           Catch_Scroll_Progress_Bar
 *
 * @wordpress-plugin
 * Plugin Name:       Catch Scroll Progress Bar
 * Plugin URI:        wordpress.org/plugins/catch-scroll-progress-bar
 * Description:       This is a simple, super-light WordPress progress bar plugin that has the most essential features to show the users how far they’ve scrolled through the current page or post
 * Version:           1.6.4
 * Author:            Catch Plugins
 * Author URI:        www.catchplugins.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       catch-scroll-progress-bar
 * Domain Path:       /languages
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
define( 'CATCH_SCROLL_PROGRESS_BAR_VERSION', '1.6.4' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-catch-scroll-progress-bar-activator.php
 */
// The URL of the directory that contains the plugin
if ( ! defined( 'CATCH_SCROLL_PROGRESS_BAR_URL' ) ) {
	define( 'CATCH_SCROLL_PROGRESS_BAR_URL', plugin_dir_url( __FILE__ ) );
}


// The absolute path of the directory that contains the file
if ( ! defined( 'CATCH_SCROLL_PROGRESS_BAR_PATH' ) ) {
	define( 'CATCH_SCROLL_PROGRESS_BAR_PATH', plugin_dir_path( __FILE__ ) );
}


// Gets the path to a plugin file or directory, relative to the plugins directory, without the leading and trailing slashes.
if ( ! defined( 'CATCH_SCROLL_PROGRESS_BAR_BASENAME' ) ) {
	define( 'CATCH_SCROLL_PROGRESS_BAR_BASENAME', plugin_basename( __FILE__ ) );
}

function activate_catch_scroll_progress_bar() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-catch-scroll-progress-bar-activator.php';
	Catch_Scroll_Progress_Bar_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-catch-scroll-progress-bar-deactivator.php
 */
function deactivate_catch_scroll_progress_bar() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-catch-scroll-progress-bar-deactivator.php';
	Catch_Scroll_Progress_Bar_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_catch_scroll_progress_bar' );
register_deactivation_hook( __FILE__, 'deactivate_catch_scroll_progress_bar' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-catch-scroll-progress-bar.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
if ( ! function_exists( 'catch_progress_bar_get_options' ) ) :
	function catch_progress_bar_get_options() {
		$defaults = catch_progress_bar_default_options();
		$options  = get_option( 'catch_progress_bar_options', $defaults );
		return wp_parse_args( $options, $defaults );
	}
endif;


if ( ! function_exists( 'catch_progress_bar_default_options' ) ) :
	/**
	 * Return array of default options
	 *
	 * @since     1.0
	 * @return    array    default options.
	 */
	function catch_progress_bar_default_options( $option = null ) {
		$default_options = array(

			'status'                => 1,
			'background_color'      => '#32dbd5',
			'foreground_opacity'    => 1,
			'background_opacity'    => 1,
			'foreground_color'      => '#dd0f0f',
			'progress_bar_height'   => '',
			'progress_bar_position' => 'top',
			'home'                  => 1,
			'blog'                  => 0,
			'archive'               => 0,
			'single'                => 0,
			'field_posttypes'       => array(),
			'bar_height'            => '7',
			'radius'                => '8',
		);

		if ( null == $option ) {
			return apply_filters( 'catch_progress_bar_deafault_options', $default_options );
		} else {
			return $default_options[ $option ];
		}
	}
endif; // catch_progress_bar_default_options
function catch_progress_bar_position() {
	$options = array(
		'top'    => esc_html__( 'Top', 'catch-scroll-progress-bar' ),
		'bottom' => esc_html__( 'Bottom', 'catch-scroll-progress-bar' ),

	);
	return $options;
}

function run_catch_scroll_progress_bar() {

	$plugin = new Catch_Scroll_Progress_Bar();
	$plugin->run();

}
run_catch_scroll_progress_bar();

/* CTP tabs removal options */
require plugin_dir_path( __FILE__ ) . '/includes/ctp-tabs-removal.php';

 $ctp_options = ctp_get_options();
if ( 1 == $ctp_options['theme_plugin_tabs'] ) {
	/* Adds Catch Themes tab in Add theme page and Themes by Catch Themes in Customizer's change theme option. */
	if ( ! class_exists( 'CatchThemesThemePlugin' ) && ! function_exists( 'add_our_plugins_tab' ) ) {
		require plugin_dir_path( __FILE__ ) . '/includes/CatchThemesThemePlugin.php';
	}
}
