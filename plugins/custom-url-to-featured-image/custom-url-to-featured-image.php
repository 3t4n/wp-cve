<?php
/**
 * Plugin Name: Add Featured Image Custom Link
 * Plugin URI: https://viitorcloud.com/blog/
 * Description: Featured Image Custom Link plugin is useful to add a custom link to the featured image of a single post/page/custom post type, which automatically links to the image in the front.
 * Version: 2.0.0
 * Author: Viitorcloud
 * Author URI: https://viitorcloud.com/
 * Text Domain: custom-url-to-featured-image
 *
 * @package Custom_URL_to_Featured_Image
 */

/**
 * Basic plugin definitions
 *
 * @package Custom Url to Featured Image
 * @since 1.1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;

if ( ! defined( 'CUST_LI_FI_DIR' ) ) {
	define( 'CUST_LI_FI_DIR', __DIR__ ); // plugin dir.
}
if ( ! defined( 'CUST_LI_FI_URL' ) ) {
	define( 'CUST_LI_FI_URL', plugin_dir_url( __FILE__ ) ); // plugin url.
}
if ( ! defined( 'CUST_LI_FI_DOMAIN' ) ) {
	define( 'CUST_LI_FI_DOMAIN', 'cust_li_fi' ); // text domain for languages.
}
if ( ! defined( 'CUST_LI_FI_PLUGIN_URL' ) ) {
	define( 'CUST_LI_FI_PLUGIN_URL', plugin_dir_url( __FILE__ ) ); // plugin url.
}
if ( ! defined( 'CUST_LI_FI_ADMIN_DIR' ) ) {
	define( 'CUST_LI_FI_ADMIN_DIR', CUST_LI_FI_DIR . '//admin' ); // plugin admin dir.
}
if ( ! defined( 'CUST_LI_FI_BASENAME' ) ) {
	define( 'CUST_LI_FI_BASENAME', 'cust-li-fi' );
}
// subtitle prefix.
if ( ! defined( 'CUST_LI_FI_META_PREFIX' ) ) {
	define( 'CUST_LI_FI_META_PREFIX', '_cust_li_fi_' );
}

/**
 * Load Text Domain
 *
 * This gets the plugin ready for translation.
 *
 * @package Custom Url to Featured Image
 * @since 1.1.0
 */

load_plugin_textdomain( 'custom-url-to-featured-image', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

/**
 * Activation hook
 *
 * Register plugin activation hook.
 *
 * @package Custom Url to Featured Image
 * @since 1.1.0
 */
register_activation_hook( __FILE__, 'cust_li_fi_install' );

/**
 * Deactivation hook
 *
 * Register plugin deactivation hook.
 *
 * @package Custom Url to Featured Image
 * @since 1.1.0
 */
register_deactivation_hook( __FILE__, 'cust_li_fi_uninstall' );

/**
 * Plugin Setup Activation hook call back
 *
 * Initial setup of the plugin setting default options
 * and database tables creations.
 *
 * @package Custom Url to Featured Image
 * @since 1.1.0
 */
function cust_li_fi_install() {

	global $wpdb;
}

/**
 * Plugin Setup (On Deactivation)
 *
 * Does the drop tables in the database and
 * delete  plugin options.
 *
 * @package Custom Url to Featured Image
 * @since 1.1.0
 */
function cust_li_fi_uninstall() {

	global $wpdb;
}
/**
 * Includes
 *
 * Includes all the needed files for our plugin
 *
 * @package Custom Url to Featured Image
 * @since 1.1.0
 */

// includes model class file.

require_once CUST_LI_FI_ADMIN_DIR . '/class-cust-li-fi-admin.php';
