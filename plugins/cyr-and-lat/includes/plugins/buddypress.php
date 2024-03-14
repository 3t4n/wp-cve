<?php
/**
 * Plugin integration BuddyPress
 * Our integration makes use of the translation of already created slugs of groups.
 *
 * @author Webcraftic <wordpress.webraftic@gmail.com>
 * @copyright (c) 11.12.2018, Webcraftic
 * @version 1.0
 */

defined( 'ABSPATH' ) || die( 'Cheatin&#8217; uh?' );

/**
 * Convert BuddyPress group slug (slug maybe urlencoded)
 */
function wbcr_ctlr_convert_buddypress_existings_slugs() {
	global $wpdb;
	
	if ( !function_exists( 'is_plugin_active' ) ) {
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	}
	
	if ( ! is_plugin_active( 'buddypress/bp-loader.php' ) ) {
		return;
	}
	
	$groups = $wpdb->get_results( "SELECT `id`, `name`, `slug` FROM {$wpdb->prefix}bp_groups WHERE slug REGEXP('%|[^_A-Za-z0-9\-]+')" );
	
	if ( is_array( $groups ) ) {
		foreach ( $groups as $group ) {
			$sanitized_slug = wbcr_ctlr_sanitize_title( urldecode( $group->slug ) );
			if ( $group->slug != $sanitized_slug ) {
				update_option( 'wbcr_bp_groups_' . $group->id . '_old_slug', $group->slug, false );
				$wpdb->update( $wpdb->prefix . 'bp_groups', array( 'slug' => $sanitized_slug ), array( 'id' => $group->id ), array( '%s' ), array( '%d' ) );
			}
		}
	}
}
