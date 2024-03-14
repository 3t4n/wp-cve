<?php
/**
 * Plugin Name: Auto Post Expiration
 * Plugin URI: https://wordpress.org/plugins/auto-post-expiration/
 * Description: This simple plugin allows to set expiry date of post and it set post to "draft" status automatic on desire expire date.
 * Version:2.0.0
 * Author: VIITORCLOUD
 * Author URI: https://viitorcloud.com/
 * License: GPL2
 *
 * @package Auto_Post_Expiration
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;

if ( ! defined( 'AUTO_POST_EXP_DIR' ) ) {
	define( 'AUTO_POST_EXP_DIR', __DIR__ ); // plugin dir.
}

if ( ! defined( 'AUTO_POST_EXP_URL' ) ) {
	define( 'AUTO_POST_EXP_URL', plugin_dir_url( __FILE__ ) ); // plugin url.
}
if ( ! defined( 'AUTO_POST_EXP_IMG_URL' ) ) {
	define( 'AUTO_POST_EXP_IMG_URL', AUTO_POST_EXP_URL . '/images' ); // plugin images url.
}
if ( ! defined( 'AUTO_POST_EXP_TEXT_DOMAIN' ) ) {
	define( 'AUTO_POST_EXP_TEXT_DOMAIN', 'auto_post_exp' ); // text domain for doing language translation.
}

/**
 * Load Text Domain
 *
 * This gets the plugin ready for translation.
 *
 * @package Auto Post Expiration
 * @since 1.0.0
 */
load_plugin_textdomain( 'auto_post_exp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
/**
 * Activation hook
 *
 * Register plugin activation hook.
 *
 * @package Auto Post Expiration
 *@since 1.0.0
 */
register_activation_hook( __FILE__, 'auto_post_exp_install' );

/**
 * Deactivation hook
 *
 * Register plugin deactivation hook.
 *
 * @package Auto Post Expiration
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'auto_post_exp_uninstall' );

/**
 * Plugin Setup Activation hook call back
 *
 * Initial setup of the plugin setting default options
 * and database tables creations.
 *
 * @package Auto Post Expiration
 * @since 1.0.0
 */
function auto_post_exp_install() {

	global $wpdb;
}
/**
 * Plugin Setup (On Deactivation)
 *
 * Does the drop tables in the database and
 * delete  plugin options.
 *
 * @package Auto Post Expiration
 * @since 1.0.0
 */
function auto_post_exp_uninstall() {

	global $wpdb;
}

/**
 * Includes
 *
 * Includes all the needed files for plugin
 *
 * @package Auto Post Expiration
 * @since 1.0.0
 */

// require_once options file.
require_once AUTO_POST_EXP_DIR . '/auto-post-expire-options.php';
