<?php
/**
 * Post Type Requirements Checklist.
 *
 * Help Clients Help Themselves
 *
 * @package   Post_Type_Requirements_Checklist
 * @author    dauidus (dave@dauid.us)
 * @license   GPL-2.0+
 * @link      http://dauid.us
 * @copyright 2014 dauid.us
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

require_once( plugin_dir_path( __FILE__ ) . 'public/class-post-type-requirements-checklist.php' );

$plugin = Post_Type_Requirements_Checklist::get_instance();
$post_types = $plugin->supported_post_types();
foreach ( $post_types as $pt ) {
	delete_option( $plugin->get_plugin_slug() . '_' . $pt );
}

	/**
	 * Change tags metabox content
	 *
	 * @param  string $content HTML string
	 *
	 * @since 1.0
	 */
	delete_option( 'aptrc-display-uninstallation-message' );

	if ( function_exists( 'is_multisite' ) && is_multisite() ) {

		if ( $network_wide ) {

			// Get all blog ids
			$blog_ids = self::get_blog_ids();

			foreach ( $blog_ids as $blog_id ) {

				delete_option( $plugin->get_plugin_slug() . '_' . $pt );

			}

			restore_current_blog();

		} else {
			delete_option( $plugin->get_plugin_slug() . '_' . $pt );
		}

	} else {
		delete_option( $plugin->get_plugin_slug() . '_' . $pt );
	}
