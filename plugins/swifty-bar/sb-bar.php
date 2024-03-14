<?php

/**
 * Swifty Bar Plugin
 *
 * @since             1.0.0
 * @package           sb_bar
 *
 * @wordpress-plugin
 * Plugin Name:       Swifty Bar, sticky bar by WPGens
 * Plugin URI:        http://wpgens.com
 * Description:       Adds sticky bar at the bottom of post that shows category, post title, author, time needed to read article, share buttons and previouse/next post links. This plugin can easly replace your social share buttons while giving readers better experience with much more options.
 * Version:           1.2.11
 * Author:            WPGens
 * Author URI:        http://wpgens.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sb-bar.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sb_bar() {

	$plugin = new sb_bar();
	$plugin->run();

}
run_sb_bar();
