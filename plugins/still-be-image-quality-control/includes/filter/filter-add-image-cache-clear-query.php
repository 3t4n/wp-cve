<?php


/**
 * クエリストリングを付け替える
 * 
 */


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




// 
add_filter( 'wp_calculate_image_srcset', function( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {

	if( empty( $image_meta['sizes'] ) ) {
		return $sources;
	}

	$new_sources = $sources;

	$upload_dir = wp_upload_dir();
	$base_dir   = $upload_dir['basedir'];

	$orig_filename = path_join( $base_dir, $image_meta['file'] );
	$sub_dir       = dirname( $orig_filename );

	foreach( $image_meta['sizes'] as $image ) {

		if( empty( $new_sources[ $image['width'] ] ) ) {
			continue;
		}

		if( apply_filters( 'stillbe_image_quality_control_force_adding_cache_clear_query', STILLBE_IQ_ENABLE_FORCE_CACHE_CLEAR )
		      ||  empty( $image['updated'] ) || empty( trim( strval( $image['updated'] ) ) ) ) {
			$timestamp = strval( @ filemtime( path_join( $sub_dir, $image['file'] ) ) );
		} else {
			$timestamp = trim( strval( $image['updated'] ) );
		}

		$new_sources[ $image['width'] ]['url'] = esc_url( add_query_arg( '_mod', $timestamp, $new_sources[ $image['width'] ]['url'] ) );

	}

	return $new_sources;

}, 10, 5 );