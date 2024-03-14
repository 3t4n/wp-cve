<?php
/**
 * Compatibility with User Submitted Posts
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/compat
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.4.0
 */

namespace Nelio_Content\Compat\User_Submitted_Posts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

add_action(
	'usp_insert_before',
	function() {
		if ( ! is_sync_post_enabled() ) {
			return;
		}//end if

		remove_sync_hooks();
		add_action(
			'usp_new_post',
			function ( $post ) {
				add_sync_hooks();
				do_action( 'nelio_content_save_post', $post['id'], true );
			}
		);
	}
);

function remove_sync_hooks() {
	$hooks = get_sync_post_hooks();
	foreach ( $hooks as $hook ) {
		remove_action( $hook[0], $hook[1] );
	}//end foreach
}//end remove_sync_hooks()

function add_sync_hooks() {
	$hooks = get_sync_post_hooks();
	foreach ( $hooks as $hook ) {
		add_action( $hook[0], $hook[1] );
	}//end foreach
}//end add_sync_hooks()

function is_sync_post_enabled() {
	$cloud    = \Nelio_Content_Cloud::instance();
	$callback = array( $cloud, 'maybe_sync_post' );
	return has_action( 'nelio_content_save_post', $callback );
}//end is_sync_post_enabled()

function get_sync_post_hooks() {
	$settings   = \Nelio_Content_Settings::instance();
	$post_types = $settings->get( 'calendar_post_types', array() );

	$hooks   = array_map(
		function ( $post_type ) {
			return "publish_{$post_type}";
		},
		$post_types
	);
	$hooks[] = 'nelio_content_save_post';

	$cloud    = \Nelio_Content_Cloud::instance();
	$callback = function() use ( &$cloud ) {
		return array( $cloud, 'maybe_sync_post' );
	};
	return array_map(
		null,
		$hooks,
		array_map( $callback, $hooks )
	);
}//end get_sync_post_hooks()
