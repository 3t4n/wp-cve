<?php
/**
 * Register Post type functionality 
 * @package Audio Player with Playlist Ultimate
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Function to register post type
 * 
 * @package Audio Player with Playlist Ultimate
 * @since 1.0.0
 */
function apwpultimate_register_post_type() {

	$apwpultimate_post_lbls = apply_filters( 'apwpultimate_post_labels', array(
								'name'					=> __( 'Audio Player', 'audio-player-with-playlist-ultimate' ),
								'singular_name'			=> __( 'Audio Player', 'audio-player-with-playlist-ultimate' ),
								'add_new'				=> __( 'Add Audio Player', 'audio-player-with-playlist-ultimate' ),
								'add_new_item'			=> __( 'Add New Audio Player', 'audio-player-with-playlist-ultimate' ),
								'edit_item'				=> __( 'Edit Audio Player', 'audio-player-with-playlist-ultimate' ),
								'new_item'				=> __( 'New Audio Player', 'audio-player-with-playlist-ultimate' ),
								'view_item'				=> __( 'View Audio Player', 'audio-player-with-playlist-ultimate' ),
								'search_items'			=> __( 'Search Audio Player', 'audio-player-with-playlist-ultimate' ),
								'not_found'				=> __( 'No Audio Player found', 'audio-player-with-playlist-ultimate' ),
								'not_found_in_trash'	=> __( 'No Audio Player found in trash', 'audio-player-with-playlist-ultimate' ),
								'parent_item_colon'		=> '',
								'menu_name'				=> __( 'Audio Player Ultimate', 'audio-player-with-playlist-ultimate' )
							));

	$apwpultimate_args = array(
		'labels'				=> $apwpultimate_post_lbls,
		'public'				=> true,
		'show_ui'				=> true,
		'query_var'				=> false,
		'rewrite'				=> array(
									'slug'			=> apply_filters( 'apwpultimate_audio_post_slug', 'audio-player' ),
									'with_front'	=> false
								),
		'capability_type'		=> 'post',
		'hierarchical'			=> false,
		'menu_icon'				=> 'dashicons-format-audio',
		'supports'				=> apply_filters( 'apwpultimate_post_supports', array( 'title', 'thumbnail' )),
	);

	// Register slick slider post type
	register_post_type( APWPULTIMATE_POST_TYPE, apply_filters( 'apwpultimate_registered_post_type_args', $apwpultimate_args ) );
}

// Action to register plugin post type
add_action('init', 'apwpultimate_register_post_type');

/**
 * Function to register taxonomy
 * 
 * @package Audio Player with Playlist Ultimate
 * @since 1.0.0
 */
function apwpultimate_register_taxonomies() {

	$apwpultimate_cat_lbls = apply_filters('apwpultimate_cat_labels', array(
		'name'				=> __( 'Playlist', 'audio-player-with-playlist-ultimate' ),
		'singular_name'		=> __( 'Playlist', 'audio-player-with-playlist-ultimate' ),
		'search_items'		=> __( 'Search Playlist', 'audio-player-with-playlist-ultimate' ),
		'all_items'			=> __( 'All Playlist', 'audio-player-with-playlist-ultimate' ),
		'parent_item'		=> __( 'Parent Playlist', 'audio-player-with-playlist-ultimate' ),
		'parent_item_colon'	=> __( 'Parent Playlist:', 'audio-player-with-playlist-ultimate' ),
		'edit_item'			=> __( 'Edit Playlist', 'audio-player-with-playlist-ultimate' ),
		'update_item'		=> __( 'Update Playlist', 'audio-player-with-playlist-ultimate' ),
		'add_new_item'		=> __( 'Add New Playlist', 'audio-player-with-playlist-ultimate' ),
		'new_item_name'		=> __( 'New Playlist Name', 'audio-player-with-playlist-ultimate' ),
		'menu_name'			=> __( 'Playlist', 'audio-player-with-playlist-ultimate' ),
	));

	$apwpultimate_cat_args = array(
		'public'			=> false,
		'hierarchical'		=> true,
		'labels'			=> $apwpultimate_cat_lbls,
		'show_ui'			=> true,
		'show_admin_column'	=> true,
		'query_var'			=> true,
		'rewrite'			=> false,
	);

	// Register Playlist
	register_taxonomy( APWPULTIMATE_CAT, array( APWPULTIMATE_POST_TYPE ), apply_filters('apwpultimate_registered_cat_args', $apwpultimate_cat_args) );
}

// Action to register plugin taxonomies
add_action( 'init', 'apwpultimate_register_taxonomies');

/**
 * Function to update post message 
 * 
 * @package Audio Player with Playlist Ultimate
 * @since 1.0.0
 */
function apwpultimate_post_updated_messages( $messages ) {

	global $post, $post_ID;

	$messages[APWPULTIMATE_POST_TYPE] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __( 'Audio Player updated.', 'audio-player-with-playlist-ultimate' ) ),
		2 => __( 'Custom field updated.', 'audio-player-with-playlist-ultimate' ),
		3 => __( 'Custom field deleted.', 'audio-player-with-playlist-ultimate' ),
		4 => __( 'Audio Player updated.', 'audio-player-with-playlist-ultimate' ),
		5 => isset( $_GET['revision'] ) ? sprintf( __( 'Audio Player restored to revision from %s', 'audio-player-with-playlist-ultimate' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __( 'Audio Player published.', 'audio-player-with-playlist-ultimate' ) ),
		7 => __( 'Audio Player saved.', 'audio-player-with-playlist-ultimate' ),
		8 => sprintf( __( 'Audio Player submitted.', 'audio-player-with-playlist-ultimate' ) ),
		9 => sprintf( __( 'Audio Player scheduled for: <strong>%1$s</strong>.', 'audio-player-with-playlist-ultimate' ),
		  date_i18n( 'M j, Y @ G:i', strtotime( $post->post_date ) ) ),
		10 => sprintf( __( 'Audio Player draft updated.', 'audio-player-with-playlist-ultimate' ) ),
	);
	return $messages;
}
// Filter to update slider post message
add_filter( 'post_updated_messages', 'apwpultimate_post_updated_messages' );