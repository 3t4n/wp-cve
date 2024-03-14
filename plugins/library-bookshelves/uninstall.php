<?php

// If plugin is not being uninstalled, exit (do nothing)
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit; }

global $wpdb;
$prefix = $wpdb->prefix;
$posts_table = $wpdb->prefix . 'posts';

// Delete plugin options
delete_option( 'library_bookshelves' );

// Delete Bookshelves posts and meta
$lbs_posts = get_posts(
	array(
		'numberposts' => -1,
		'post_status' => array( 'any', 'trash', 'auto-draft' ),
		'post_type'   => 'bookshelves'
	)
);

if ( ! empty( $lbs_posts ) ) {
	foreach ( $lbs_posts as $post ) {
		// Unschedule cron update job if one exists
		$hook = 'update_bookshelf';
		$args = array( strval( $post->ID ) );
		$timestamp = wp_next_scheduled( $hook, $args );

		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, $hook, $args );
		}

		wp_delete_post( $post->ID );
	}
}

// Delete widget data
delete_option( 'widget_bookshelves_widget' ); // Remove after all installs are >= 5.0

// Delete taxonomies
$lbs_taxonomy = 'location';

$lbs_terms = $wpdb->get_results( $wpdb->prepare( "SELECT t.*, tt.* FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN (%s) ORDER BY t.name ASC", $lbs_taxonomy ) );

// Delete Terms
if ( $lbs_terms ) {
	foreach ( $lbs_terms as $lbs_term ) {
		$wpdb->delete(
			$wpdb->term_taxonomy,
			array( 'term_taxonomy_id' => $lbs_term->term_taxonomy_id )
		);

		$wpdb->delete(
			$wpdb->terms,
			array( 'term_id' => $lbs_term->term_id )
		);
	}
}

// Delete Taxonomy
$wpdb->delete(
	$wpdb->term_taxonomy,
	array( 'taxonomy' => $lbs_taxonomy ),
	array( '%s' )
);

$options_table  = $wpdb->prefix . 'options';
$like_lbs = $wpdb->esc_like( 'lbs_' ) . '%';

// Query WP options table for entries beginning with the prefix set in $Bookshelves_Settings->base
$wpdb->query( $wpdb->prepare( 'DELETE FROM %1s WHERE option_name like %s', array( $options_table, $like_lbs ) ) );
