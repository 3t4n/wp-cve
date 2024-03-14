<?php
/**
 * Uninstall
 *
 * @package Organize Media Folder
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

global $wpdb;
$termids = array();
$termids = $wpdb->get_col(
	"
	SELECT term_id
	FROM {$wpdb->prefix}term_taxonomy
	WHERE taxonomy = 'omf_folders'
	"
);

/* For Single site */
if ( ! is_multisite() ) {
	delete_option( 'omf_admin' );
	$blogusers = get_users( array( 'fields' => array( 'ID' ) ) );
	foreach ( $blogusers as $user ) {
		delete_user_option( $user->ID, 'omf_per_page', false );
		delete_user_option( $user->ID, 'omf_filter_user', false );
		delete_user_option( $user->ID, 'omf_filter_mime_type', false );
		delete_user_option( $user->ID, 'omf_search_text', false );
		delete_user_option( $user->ID, 'omf_filter_term', false );
		delete_user_option( $user->ID, 'omf_filter_monthly', false );
		delete_user_option( $user->ID, 'omf_current_logs', false );
	}
	if ( ! empty( $termids ) ) {
		foreach ( $termids as $termid ) {
			$where_format = array( '%d' );
			$delete_line = array( 'term_id' => $termid );
			$delete_line_relationships = array( 'term_taxonomy_id' => $termid );
			$wpdb->delete( $wpdb->prefix . 'terms', $delete_line, $where_format );
			$wpdb->delete( $wpdb->prefix . 'term_taxonomy', $delete_line, $where_format );
			$wpdb->delete( $wpdb->prefix . 'term_relationships', $delete_line_relationships, $where_format );
		}
	}
} else {
	/* For Multisite */
	$blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->prefix}blogs" );
	$original_blog_id = get_current_blog_id();
	foreach ( $blog_ids as $blogid ) {
		switch_to_blog( $blogid );
		delete_option( 'omf_admin' );
		$blogusers = get_users(
			array(
				'blog_id' => $blogid,
				'fields' => array( 'ID' ),
			)
		);
		foreach ( $blogusers as $user ) {
			delete_user_option( $user->ID, 'omf_per_page', false );
			delete_user_option( $user->ID, 'omf_filter_user', false );
			delete_user_option( $user->ID, 'omf_filter_mime_type', false );
			delete_user_option( $user->ID, 'omf_search_text', false );
			delete_user_option( $user->ID, 'omf_filter_term', false );
			delete_user_option( $user->ID, 'omf_filter_monthly', false );
			delete_user_option( $user->ID, 'omf_current_logs', false );
		}
		if ( ! empty( $termids ) ) {
			foreach ( $termids as $termid ) {
				$where_format = array( '%d' );
				$delete_line = array( 'term_id' => $termid );
				$delete_line_relationships = array( 'term_taxonomy_id' => $termid );
				$wpdb->delete( $wpdb->prefix . 'terms', $delete_line, $where_format );
				$wpdb->delete( $wpdb->prefix . 'term_taxonomy', $delete_line, $where_format );
				$wpdb->delete( $wpdb->prefix . 'term_relationships', $delete_line_relationships, $where_format );
			}
		}
	}
	switch_to_blog( $original_blog_id );
}
