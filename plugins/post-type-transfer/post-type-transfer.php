<?php
/**
 * Plugin Name:     Post Type Transfer
 * Plugin URI:      https://wordpress.org/plugins/post-type-transfer/
 * Description:     This plugin will allow user to change post type to other public post types or page.
 * Author:          KrishaWeb
 * Author URI:      https://www.krishaweb.com/
 * Text Domain:     post-type-transfer
 * Domain Path:     /languages
 * Version:         1.4
 *
 * @package         Post_Type_Transfer
 */

if ( ! defined( 'ABSPATH' ) ) exit;

require_once plugin_dir_path( __FILE__ ) . 'admin/class-post-type-transfer-admin.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/class-quick-edit.php';
require_once plugin_dir_path( __FILE__ ) . 'lib/block/class-ptt-guntenberg-metabox.php';

/**
 * Load plugin textdomain.
 */
add_action( 'plugins_loaded', 'ptt_textdomain' );
function ptt_textdomain() {
	load_plugin_textdomain( 'post-type-transfer', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

/**
 * Plugin activate hook.
 */
register_activation_hook( __FILE__ , 'ptt_activate' );
function ptt_activate() {
	// Activation code here...
}

/**
 * Plugin deactivate hook.
 */
register_deactivation_hook( __FILE__ , 'ptt_deactivate' );
function ptt_deactivate() {
	// Deactivation code here...
}

/**
 * Plugin class init.
 */
function ptt_init() {
	new Post_Type_Transfer;
}
ptt_init();
