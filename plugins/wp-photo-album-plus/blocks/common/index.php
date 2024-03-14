<?php
/**
 * WPPA common block functions
 *
 * Version: 8.5.01.001
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_footer', 'wppa_block_js', 1 );

// Make the js for wppa blocks
function wppa_block_js() {
global $wpdb;

	// Init
	$the_js = '';

	/* Make the album list  wppaAlbumList */
	{
		// Get all album names and ids
		$albums = $wpdb->get_results( 	"SELECT id, name
										FROM $wpdb->wppa_albums",
										ARRAY_A
									);

		// Add paths
		$albums = wppa_add_paths( $albums );

		// Sort
		$albums = wppa_array_sort( $albums, 'name' );

		// Now make the list
		$the_js .= '
		var wppaAlbumList = [{label: "' . __( 'Select an album', 'wp-photo-album-plus' ) . '", value: "0"},';
		foreach( $albums as $album ) {
			$the_js .= '
			{ label: "' . str_replace( '&gt;', '>', trim( addslashes( $album['name'] ) ) ) . '", value: "' . $album['id'] . '" },';
		}
		$the_js .= ']';
	}

	/* Make the photo list  wppaPhotoList */
	{
		// Get the first 250 photo names and ids
		$photos = $wpdb->get_results( 	"SELECT id, name
										FROM $wpdb->wppa_photos
										ORDER BY timestamp DESC
										LIMIT 250",
										ARRAY_A
									);

		// Sort
		$photos = wppa_array_sort( $photos, 'name' );

		// Now make the list
		$the_js .= '
		var wppaPhotoList = [{label: "' . __( 'Select a photo', 'wp-photo-album-plus' ) . '", value: "0"},';
		foreach( $photos as $photo ) {
			$the_js .= '
			{ label: "' . str_replace( '&gt;', '>', trim( addslashes( $photo['name'] ) ) ) . '", value: "' . $photo['id'] . '" },';
		}
		$the_js .= ']';
	}

	// Load the js for wppa blocks
	wppa_add_inline_script( 'wppa-admin', $the_js, true );
}

// Make the styles for wppa blocks
add_action( 'admin_init', 'wppa_block_styles',10 );

function wppa_block_styles() {

	$file = 'wppa-block-styles.css';
	$path = dirname( __FILE__ ) . '/' . $file;
	$ver  = filemtime( $path );
	wp_register_style( 'wppa_block_style', WPPA_URL . '/blocks/common/' . $file, '', $ver );
	wp_enqueue_style( 'wppa_block_style' );
}
