<?php
/**
 * Plugin integration Advanced custom fields
 * Our integration uses the translation of already created slugs of forums and topics.
 *
 * @author Webcraftic <wordpress.webraftic@gmail.com>
 * @copyright (c) 11.12.2018, Webcraftic
 * @version 1.0
 */

defined( 'ABSPATH' ) || die( 'Cheatin&#8217; uh?' );

/**
 * Convert Asgaros forum and topic slugs
 */
function wbcr_ctlr_conver_asgaros_forum_existing_slugs() {
	global $wpdb;
	
	if ( !function_exists( 'is_plugin_active' ) ) {
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	}
	
	if ( ! is_plugin_active( 'asgaros-forum/asgaros-forum.php' ) ) {
		return;
	}
	
	$groups = $wpdb->get_results( "SELECT `id`, `name`, `slug` FROM {$wpdb->prefix}forum_forums WHERE slug REGEXP('%|[^_A-Za-z0-9\-]+')" );
	
	if ( is_array( $groups ) ) {
		foreach ( $groups as $group ) {
			$sanitized_slug = wbcr_ctlr_sanitize_title( urldecode( $group->slug ) );
			if ( $group->slug != $sanitized_slug ) {
				update_option( 'wbcr_asgaros_forums_' . $group->id . '_old_slug', $group->slug, false );
				$wpdb->update( $wpdb->prefix . 'forum_forums', array( 'slug' => $sanitized_slug ), array( 'id' => $group->id ), array( '%s' ), array( '%d' ) );
			}
		}
	}
	
	$groups = $wpdb->get_results( "SELECT `id`, `name`, `slug` FROM {$wpdb->prefix}forum_topics WHERE slug REGEXP('%|[^_A-Za-z0-9\-]+')" );
	
	if ( is_array( $groups ) ) {
		foreach ( $groups as $group ) {
			$sanitized_slug = wbcr_ctlr_sanitize_title( urldecode( $group->slug ) );
			if ( $group->slug != $sanitized_slug ) {
				update_option( 'wbcr_asgaros_topics_' . $group->id . '_old_slug', $group->slug, false );
				$wpdb->update( $wpdb->prefix . 'forum_topics', array( 'slug' => $sanitized_slug ), array( 'id' => $group->id ), array( '%s' ), array( '%d' ) );
			}
		}
	}
}


