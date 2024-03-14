<?php

/**
 * The main plugin file of WP Post Nav
 *
 * @link:       https://en-gb.wordpress.org/plugins/wp-post-nav/
 * @since       0.0.1
 * @package     wp_post_nav
 *
 * @wordpress-plugin
 * Plugin Name:       WP Post Nav
 * Plugin URI:        https://en-gb.wordpress.org/plugins/wp-post-nav/
 * Description:       Wordpress Posts Navigation Plugin.  Navigate between posts, pages and custom post types with ease.
 * Version:           2.0.3
 * Author:            jo4nny8
 * Author URI:        https://profiles.wordpress.org/jo4nny8/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-post-nav
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('ABSPATH') ) {
	exit;
}

//Activation File
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-post-nav-activator.php';

//Deactiviation File
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-post-nav-deactivator.php';

//Activiation Hook
register_activation_hook( __FILE__, array( 'wp_post_nav_Activator', 'activate' ) );

//Deactivation Hook - Not used in this plugin
register_activation_hook( __FILE__, array( 'wp_post_nav_Deactivator', 'deactivate' ) );

//Initiate the main class and file
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-post-nav.php';

//Start the plugin
function run_wp_post_nav() {

	$plugin = new wp_post_nav();
	$plugin->run();

}
run_wp_post_nav();