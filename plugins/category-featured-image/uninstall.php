<?php
/**
 * Uninstall
 *
 * @package Category Featured Image
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

global $wpdb;
/* Option name */
$option_names = array();
$wp_options = $wpdb->get_results(
	"
	SELECT option_name
	FROM {$wpdb->prefix}options
	WHERE option_name LIKE '%%categoryfeaturedimage%%'
	"
);
foreach ( $wp_options as $wp_option ) {
	$option_names[] = $wp_option->option_name;
}

/* termmeta term_id */
$term_ids = array();
$wp_termmetas = $wpdb->get_results(
	"
	SELECT term_id
	FROM {$wpdb->prefix}termmeta
	WHERE meta_key = 'featured_image_id'
	"
);
foreach ( $wp_termmetas as $wp_termmeta ) {
	$term_ids[] = $wp_termmeta->term_id;
}

/* for postmeta */
$args = array(
	'post_type' => 'post',
	'numberposts' => -1,
);
$allposts = get_posts( $args );

/* For Single site */
if ( ! is_multisite() ) {
	foreach ( $option_names as $option_name ) {
		delete_option( $option_name );
	}
	foreach ( $term_ids as $term_id ) {
		delete_term_meta( $term_id, 'featured_image_id' );
	}
	foreach ( $allposts as $postinfo ) {
		delete_post_meta( $postinfo->ID, 'categoryfeaturedimage_exclude' );
	}
} else {
	/* For Multisite */
	$blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->prefix}blogs" );
	$original_blog_id = get_current_blog_id();
	foreach ( $blog_ids as $blogid ) {
		switch_to_blog( $blogid );
		foreach ( $option_names as $option_name ) {
			delete_option( $option_name );
		}
		foreach ( $term_ids as $term_id ) {
			delete_term_meta( $term_id, 'featured_image_id' );
		}
		foreach ( $allposts as $postinfo ) {
			delete_post_meta( $postinfo->ID, 'categoryfeaturedimage_exclude' );
		}
	}
	switch_to_blog( $original_blog_id );

	/* For site options. */
	foreach ( $option_names as $option_name ) {
		delete_site_option( $option_name );
	}
	foreach ( $term_ids as $term_id ) {
		delete_term_meta( $term_id, 'featured_image_id' );
	}
	foreach ( $allposts as $postinfo ) {
		delete_post_meta( $postinfo->ID, 'categoryfeaturedimage_exclude' );
	}
}
