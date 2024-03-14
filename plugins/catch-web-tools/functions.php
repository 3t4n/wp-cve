<?php
/**
 * Plugin Name: Catch Web Tools
 * Plugin URI: https://catchplugins.com/plugins/catch-web-tools/
 * Description: Catch Web Tools is a modular plugin that powers up your WordPress site with simple and utilitarian features. It currently offers Webmaster Tool, Open Graph, Custom CSS, Social Icons, Security, Updator and Basic SEO optimization modules with more addition in updates to come.
 * Author: Catch Plugins
 * Author URI:  https://catchplugins.com/
 * Version: 2.7.4
 * License: GNU General Public License, version 3 (GPLv3)
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Requires at least: 5.7
 *
 * Text Domain: catch-web-tools
 * Domain Path: /catch-web-tools/
 *
 * @package Catch Plugins
 * @subpackage Catch Web Tools
 * @author CatchThemes
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// Define Version
define( 'CATCHWEBTOOLS_VERSION', '2.7.4' );

// The URL of the directory that contains the plugin
if ( ! defined( 'CATCHWEBTOOLS_URL' ) ) {
	define( 'CATCHWEBTOOLS_URL', plugin_dir_url( __FILE__ ) );
}


// The absolute path of the directory that contains the file
if ( ! defined( 'CATCHWEBTOOLS_PATH' ) ) {
	define( 'CATCHWEBTOOLS_PATH', plugin_dir_path( __FILE__ ) );
}


// Gets the path to a plugin file or directory, relative to the plugins directory, without the leading and trailing slashes.
if ( ! defined( 'CATCHWEBTOOLS_BASENAME' ) ) {
	define( 'CATCHWEBTOOLS_BASENAME', plugin_basename( __FILE__ ) );
}


/**
 * Make plugin available for translation
 * Translations can be filed in the /languages/ directory
 */
function catchwebtools_load_textdomain() {
	load_plugin_textdomain( 'catch-web-tools', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'catchwebtools_load_textdomain', 1 );


/**
 * Compare PHP Version
 *
 * Activate only if PHP version 5.2 or above
 */
if ( version_compare( PHP_VERSION, '5.2', '<' ) ) {
	if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
		require_once ABSPATH . '/wp-admin/includes/plugin.php';

		deactivate_plugins( __FILE__ );

		wp_die( sprintf( esc_html__( 'Catch Web Tools requires PHP 5.2 or higher, as does WordPress 3.5 and higher. The plugin has now disabled itself.', 'catch-web-tools' ) ) );
	} else {
		return;
	}
}

/**
 * Function to get catchwebtools options from parameter
 *
 * @param $field:option field name
 */
function catchwebtools_get_options( $field ) {
	$defaults = array();

	if ( 'catchwebtools_to_top_options' == $field ) {
		$defaults = catchwebtools_to_top_default_options();
	} elseif ( 'catchwebtools_webmaster' == $field ) {
		$defaults = catchwebtools_webmaster_default_options();
	} elseif ( 'catchwebtools_seo' == $field ) {
		$defaults = catchwebtools_seo_default_options();
	} elseif ( 'catchwebtools_opengraph' == $field ) {
		$defaults = catchwebtools_og_default_options();
	} elseif ( 'catchwebtools_catchids' == $field ) {
		$defaults = catchwebtools_catch_ids_default_options();
	} elseif ( 'catchwebtools_catch_updater' == $field ) {
		$defaults = catchwebtools_catch_updater_default_options();
	} elseif ( 'catchwebtools_social' == $field ) {
		$defaults = catchwebtools_social_default_options();
	} elseif ( 'catchwebtools_big_image_size_threshold' == $field ) {
		$defaults = catchwebtools_big_image_size_threshold_default_options();
	}

	$options = get_option( $field, $defaults );

	if ( is_array( $options ) ) {
		return array_merge( $defaults, $options );
	}

	return $options;
}

// Include default options
require_once( CATCHWEBTOOLS_PATH . '/admin/inc/default-options.php' );


// Include Admin functions
require_once CATCHWEBTOOLS_PATH . '/admin/admin-functions.php';


// Include Frontend functions
require_once CATCHWEBTOOLS_PATH . '/frontend/frontend-functions.php';

function catchwebtools_action_links( $links, $plugin_file ) {
	if ( ! isset( $plugin ) ) {
		$plugin = plugin_basename( __FILE__ );
	}

	if ( $plugin == $plugin_file ) {
		$cwt_dashboard = add_query_arg(
			array(
				'page' => 'catch-web-tools',
			),
			admin_url( 'admin.php' )
		);

		$settings_link = '<a href="' . esc_url( $cwt_dashboard ) . '">' . esc_html__( 'Settings', 'catch-web-tools' ) . '</a>';

		array_unshift( $links, $settings_link );
	}

	return $links;
}
add_filter( 'plugin_action_links', 'catchwebtools_action_links', 10, 2 );

/* CTP tabs removal options */
require plugin_dir_path( __FILE__ ) . 'admin/inc/ctp-tabs-removal.php';

 $ctp_options = ctp_get_options();
if ( 1 == $ctp_options['theme_plugin_tabs'] ) {
	/* Adds Catch Themes tab in Add theme page and Themes by Catch Themes in Customizer's change theme option. */
	if ( ! class_exists( 'CatchThemesThemePlugin' ) && ! function_exists( 'add_our_plugins_tab' ) ) {
		require plugin_dir_path( __FILE__ ) . 'admin/inc/CatchThemesThemePlugin.php';
	}
}
