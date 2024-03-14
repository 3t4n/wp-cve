<?php
/**
 * Plugin Name: Hreflang Manager
 * Description: Set language and regional URL for better SEO performance. (Lite Version)
 * Version: 1.07
 * Author: DAEXT
 * Author URI: https://daext.com
 * Text Domain: hreflang-manager-lite
 *
 * @package hreflang-manager-lite
 */

// Prevent direct access to this file.
if ( ! defined( 'WPINC' ) ) {
	die(); }

// Class shared across public and admin.
require_once plugin_dir_path( __FILE__ ) . 'shared/class-daexthrmal-shared.php';

// Public.
require_once plugin_dir_path( __FILE__ ) . 'public/class-daexthrmal-public.php';
add_action( 'plugins_loaded', array( 'Daexthrmal_Public', 'get_instance' ) );

// Admin.
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	// Admin.
	require_once plugin_dir_path( __FILE__ ) . 'admin/class-daexthrmal-admin.php';
	add_action( 'plugins_loaded', array( 'Daexthrmal_Admin', 'get_instance' ) );

	// Activate.
	register_activation_hook( __FILE__, array( Daexthrmal_Admin::get_instance(), 'ac_activate' ) );

}

/**
 * Customize the action links in the "Plugins" menu.
 *
 * @param array $actions An array of plugin action links.
 *
 * @return mixed
 */
function daexthrmal_customize_action_links( $actions ) {
	$actions[] = '<a href="https://daext.com/hreflang-manager/">' . esc_html__( 'Buy the Pro Version', 'hreflang-manager-lite' ) . '</a>';
	return $actions;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'daexthrmal_customize_action_links' );
