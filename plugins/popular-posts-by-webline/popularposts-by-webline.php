<?php
/**
 * Plugin Name: Popular Posts by Webline
 * Plugin URI: http://www.weblineindia.com
 * Description: This plugin is used to show the Popular Posts as per the different filters applied on it. This is very simple and light plugin and easy to use.
 * Author: Weblineindia
 * Version: 1.0.8
 * Author URI: http://www.weblineindia.com
 * License: GPL
 * Text Domain: popular-posts-by-webline
 * Domain Path: /languages
 */

// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

require_once ( ABSPATH . 'wp-admin/includes/plugin.php' );

$plugin_data = get_plugin_data( __FILE__ );

define( 'WLIPOPULARPOSTS_VERSION', $plugin_data['Version'] );
define( 'PP_DEBUG', FALSE );
define( 'PP_PATH', plugin_dir_path( __FILE__ ) );
define( 'PP_URL', plugins_url( '', __FILE__ ) );
define( 'PP_PLUGIN_FILE', basename( __FILE__ ) );
define( 'PP_PLUGIN_DIR', plugin_basename( dirname( __FILE__ ) ) );
define( 'PP_ADMIN_DIR', PP_PATH . 'admin' );

/**
 * Load the plugin's translated string when plugin is loaded.
 */
add_action( 'plugins_loaded', 'wli_popular_posts_plugin_loaded' );
function wli_popular_posts_plugin_loaded() {
	load_plugin_textdomain( 'popular-posts-by-webline', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_filter( 'plugin_action_links_'  . plugin_basename(__FILE__), 'wli_add_popular_posts_settings_link');
//Function for admin menu link
function wli_add_popular_posts_settings_link($links_array)
{
    array_unshift($links_array, '<a href="' . admin_url('options-general.php?page=wli-popular-posts-by-webline') . '">Settings</a>');
    return $links_array;
}

require_once ( PP_ADMIN_DIR . '/class/hook.php' );

require_once ( PP_ADMIN_DIR . '/class/popular-posts.php' );
?>
