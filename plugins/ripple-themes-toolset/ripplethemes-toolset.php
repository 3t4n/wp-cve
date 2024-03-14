<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*
Plugin Name: Ripple Themes Toolset
Description: Install One Click Demo Import Plugin First. Import the demos of Ripple Themes Product. The activated themes demo data will show under Appearance > Demo Import.
Version:     1.0.7
Author:      Ripple Themes
Author URI:  https://ripplethemes.com/
License:     GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: ripplethemes-toolset
*/

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
define( 'RIPPLETHEMES_TOOLSET_PATH', plugin_dir_path( __FILE__ ) );
define( 'RIPPLETHEMES_TOOLSET_PLUGIN_NAME', 'ripplethemes-toolset' );
define( 'RIPPLETHEMES_TOOLSET_VERSION', '1.0.7' );
define( 'RIPPLETHEMES_TOOLSET_URL', plugin_dir_url( __FILE__ ) );
define( 'RIPPLETHEMES_TOOLSET_TEMPLATE_URL', RIPPLETHEMES_TOOLSET_URL . 'inc/demo/' );

require RIPPLETHEMES_TOOLSET_PATH . 'inc/init.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.2
 */
if ( ! function_exists( 'run_ripplethemes_toolset' ) ) {

	function run_ripplethemes_toolset() {

		return Ripplethemes_Toolset::instance();
	}
	run_ripplethemes_toolset()->run();
}
